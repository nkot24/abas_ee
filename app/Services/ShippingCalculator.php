<?php

namespace App\Services;

use App\Models\ShippingRate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ShippingCalculator
{
    private const BALTIC = ['LT', 'LV', 'EE'];

    public static function calculate(string $country, string $method, array $items, \Illuminate\Support\Collection $products): float
    {
        $country = strtoupper($country);

        if ($method === 'locker') {
            $group = $country === 'LV' ? 'lv' : 'baltic';
            return (float) (static::rates($group, 'locker')->first()?->price ?? ($country === 'LV' ? 2.50 : 3.05));
        }

        $totalWeight = static::calcWeight($country, $items, $products);

        $group = $country === 'LV' ? 'lv' : 'baltic';

        $rates = static::rates($group, 'courier');
        $price = (float) ($rates->last()?->price ?? 79.99);
        foreach ($rates as $rate) {
            if ($totalWeight <= $rate->weight_up_to) {
                $price = (float) $rate->price;
                break;
            }
        }

        return $price;
    }

    public static function calcWeight(string $country, array $items, \Illuminate\Support\Collection $products): float
    {
        $country  = strtoupper($country);
        $isBaltic = in_array($country, self::BALTIC);
        $total    = 0.0;

        foreach ($items as $item) {
            $product = is_array($products) ? ($products[$item['id']] ?? null) : ($products[$item['id']] ?? null);
            if (!$product) continue;
            $qty    = (int) $item['qty'];
            $actual = (float) ($product->weight ?? 5) * $qty;

            if ($isBaltic) {
                $total += $actual;
            } else {
                $l = (float) ($product->length ?? 30);
                $w = (float) ($product->width  ?? 30);
                $h = (float) ($product->height ?? 30);
                $total += max($actual, ($l * $w * $h / 5000) * $qty);
            }
        }

        return $total;
    }

    public static function lockerEligible(array $items, \Illuminate\Support\Collection $products): bool
    {
        // Locker sizes (H × W × D in cm), all packages max 30 kg
        $lockers = [
            [8.5,  16.0, 58.0], // XS
            [8.5,  38.0, 58.0], // S
            [18.0, 38.0, 58.0], // M
            [38.0, 38.0, 58.0], // L (largest)
        ];
        foreach ($lockers as &$lk) { sort($lk); }
        unset($lk);

        $lVolume   = 38 * 38 * 58; // L locker cm³
        $maxWeight = 30.0;

        $totalWeight = 0.0;
        $totalVolume = 0.0;

        foreach ($items as $item) {
            $product = $products[$item['id']] ?? null;
            if (!$product) continue;
            $qty = (int) $item['qty'];

            $l = (float) ($product->length ?? 0);
            $w = (float) ($product->width  ?? 0);
            $h = (float) ($product->height ?? 0);

            // Dimensions must be entered — without them we can't confirm it fits
            if ($l <= 0 || $w <= 0 || $h <= 0) return false;

            $dims = [$l, $w, $h];
            sort($dims);

            // Every individual item must fit in at least one locker size
            $fits = false;
            foreach ($lockers as $lk) {
                if ($dims[0] <= $lk[0] && $dims[1] <= $lk[1] && $dims[2] <= $lk[2]) {
                    $fits = true;
                    break;
                }
            }
            if (!$fits) return false;

            $totalVolume += $l * $w * $h * $qty;

            $weight = (float) ($product->weight ?? 0);
            if ($weight > 0) {
                $totalWeight += $weight * $qty;
            }
        }

        if ($totalWeight > $maxWeight) return false;
        if ($totalVolume > 0 && $totalVolume > $lVolume) return false;

        return true;
    }

    public static function rates(string $group, string $method): Collection
    {
        $data = Cache::remember("shipping_rates_{$group}_{$method}", 3600, function () use ($group, $method) {
            return ShippingRate::where('country_group', $group)
                ->where('method', $method)
                ->orderBy('sort')
                ->get()
                ->toArray();
        });
        return collect($data)->map(fn ($r) => (object) $r);
    }

    public static function clearCache(): void
    {
        $groups  = ['lv', 'baltic'];
        $methods = ['locker', 'courier'];
        foreach ($groups as $g) {
            foreach ($methods as $m) {
                Cache::forget("shipping_rates_{$g}_{$m}");
            }
        }
    }
}
