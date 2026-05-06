<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LatvijasPostsService
{
    private const BASE_URL = 'https://express.pasts.lv';

    // Known country IDs from EKIS API (Latvia=7, Lithuania=2 from official docs)
    private const COUNTRY_IDS = [
        'LV' => 7,
        'LT' => 2,
    ];

    private string $secret;

    public function __construct()
    {
        $this->secret = config('shipping.secret', '');
    }

    public function isConfigured(): bool
    {
        return $this->secret !== '';
    }

    public function registerShipment(Order $order): ?string
    {
        if (!$this->isConfigured()) {
            Log::info('LatvijasPassts: secret not set, skipping label generation for order ' . $order->id);
            return null;
        }

        try {
            $countryId = $this->resolveCountryId(strtoupper($order->country));
            if (!$countryId) {
                Log::error('LatvijasPassts: unknown country ' . $order->country . ' for order ' . $order->id);
                return null;
            }

            $internalId = 'order' . $order->id;
            $parcel     = $this->buildParcel($order, $countryId);

            $response = Http::asForm()
                ->timeout(15)
                ->post(self::BASE_URL . '/parcelsApi/create', [
                    'secret'  => $this->secret,
                    'parcels' => [$internalId => $parcel],
                ]);

            Log::info('LatvijasPassts: API response for order ' . $order->id, [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            if (!$response->successful()) {
                Log::error('LatvijasPassts: create failed for order ' . $order->id);
                return null;
            }

            $data = $response->json();

            // API returns { "internalId": "CE123LV" } on success or { "internalId": { "field": "error" } } on failure
            if (isset($data[$internalId]) && is_array($data[$internalId])) {
                $errors = $data[$internalId];
                Log::error('LatvijasPassts: API validation error for order ' . $order->id, $errors);
                $this->notifyAdminOfFailure($order, $errors);
                return null;
            }

            // Also handle top-level error like { "error": "Unknown user" }
            if (isset($data['error'])) {
                Log::error('LatvijasPassts: API error for order ' . $order->id . ': ' . $data['error']);
                $this->notifyAdminOfFailure($order, ['error' => $data['error']]);
                return null;
            }

            $tracking = $data[$internalId] ?? null;

            if (!$tracking || !is_string($tracking)) {
                Log::error('LatvijasPassts: no tracking number in response for order ' . $order->id, $data);
                $this->notifyAdminOfFailure($order, ['error' => 'No tracking number returned', 'response' => $data]);
                return null;
            }

            $order->update(['tracking_number' => $tracking]);

            $this->downloadLabel($order, $tracking);

            Log::info('LatvijasPassts: label generated for order ' . $order->id . ', tracking: ' . $tracking);

            return $tracking;
        } catch (\Exception $e) {
            Log::error('LatvijasPassts: exception for order ' . $order->id . ': ' . $e->getMessage());
            return null;
        }
    }

    private function buildParcel(Order $order, int $countryId): array
    {
        $country = strtoupper($order->country);
        $isLocker = $order->parcel_locker_id !== null;

        [$street, $house] = $this->splitAddress($order->address);

        $type = match (true) {
            $country === 'LV'              => 'Ie',
            in_array($country, ['LT','EE']) => 'Be',
            default                         => 'Ems',
        };

        $parcel = [
            'type'         => $type,
            'name_surname' => $order->first_name . ' ' . $order->last_name,
            'email'        => $order->email,
            'country_id'   => $countryId,
            'zipcode'      => $this->formatPostalCode($order->postal_code, $country),
        ];

        $parcel['phone'] = preg_replace('/[^\d]/', '', $order->phone);

        // SMS invite only for Latvia (other countries require local phone format for SMS)
        if ($country === 'LV') {
            $parcel['sms_invite'] = 1;
        }

        if ($isLocker && $country === 'LV') {
            $parcel['station_id'] = $order->parcel_locker_id;
        } elseif ($isLocker && $country === 'LT') {
            $parcel['terminal_id'] = $order->parcel_locker_id;
            $parcel['size']        = 'Medium';
            $parcel['city']        = $order->city;
            $parcel['zipcode']     = $order->postal_code;
        } else {
            $parcel['city']   = $order->city;
            $parcel['street'] = $street;
            $parcel['house']  = $house;

            if ($country === 'LV') {
                $parcel['delivery_priority'] = 'X1';
            }
        }

        return $parcel;
    }

    private function downloadLabel(Order $order, string $tracking): void
    {
        try {
            $response = Http::asForm()
                ->timeout(15)
                ->post(self::BASE_URL . '/parcelsDocumentsApi/stickers', [
                    'secret'  => $this->secret,
                    'size'    => '150x100',
                    'parcels' => [$tracking],
                ]);

            if ($response->successful()) {
                $path = 'labels/order_' . $order->id . '_' . $tracking . '.pdf';
                Storage::put($path, $response->body());
                $order->update(['label_path' => $path]);
            }
        } catch (\Exception $e) {
            Log::warning('LatvijasPassts: could not download label for ' . $tracking . ': ' . $e->getMessage());
        }
    }

    private function resolveCountryId(string $iso): ?int
    {
        if (isset(self::COUNTRY_IDS[$iso])) {
            return self::COUNTRY_IDS[$iso];
        }

        // Fetch and cache the full country list for unknown countries
        $countries = Cache::remember('latvijas_pasts_countries', 86400, function () {
            $response = Http::timeout(10)->get(self::BASE_URL . '/countriesApi/index');
            return $response->successful() ? $response->json() : [];
        });

        foreach ($countries as $country) {
            if (isset($country['iso']) && strtoupper($country['iso']) === $iso) {
                return (int) $country['id'];
            }
        }

        return null;
    }

    private function notifyAdminOfFailure(Order $order, array $errors): void
    {
        $errorLines = implode("\n", array_map(
            fn($field, $msg) => "  • {$field}: {$msg}",
            array_keys($errors),
            array_values($errors)
        ));

        \Illuminate\Support\Facades\Mail::raw(
            "Latvijas Pasts label generation FAILED for order #{$order->id}\n\n" .
            "Customer: {$order->first_name} {$order->last_name} ({$order->email})\n" .
            "Destination: {$order->address}, {$order->city}, {$order->country}\n\n" .
            "Errors:\n{$errorLines}\n\n" .
            "Please create the label manually in the Latvijas Pasts portal.",
            fn($msg) => $msg
                ->to(env('ADMIN_EMAIL', 'niksindriksons2006@gmail.com'))
                ->subject("⚠ Label failed – Order #{$order->id}")
        );
    }

    private function formatPostalCode(string $postal, string $country): string
    {
        $postal = trim($postal);

        if ($country === 'LV') {
            // LP expects LV-XXXX format
            $digits = preg_replace('/[^\d]/', '', $postal);
            if (strlen($digits) === 4) {
                return 'LV-' . $digits;
            }
        }

        return $postal;
    }

    private function splitAddress(string $address): array
    {
        // Strip city suffix after comma: "Sabiles iela 2, Kandava" -> "Sabiles iela 2"
        $streetPart = trim(explode(',', $address)[0]);

        if (preg_match('/^(.+?)\s+(\d+\S*)$/', $streetPart, $m)) {
            return [$m[1], $m[2]];
        }
        return [$streetPart, ''];
    }
}
