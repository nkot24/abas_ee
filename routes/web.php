<?php

use App\Mail\DataDeleted;
use App\Mail\OrderConfirmed;
use App\Mail\OrderPlaced;
use App\Models\Order;
use App\Models\PageSetting;
use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Stichoza\GoogleTranslate\GoogleTranslate;

function translateSetting(string $page, string $key, string $value): string
{
    $cacheKey = "setting_en_{$page}_{$key}";
    return Cache::rememberForever($cacheKey, function () use ($value) {
        try {
            $tr = new GoogleTranslate('en');
            $tr->setSource('lt');
            return $tr->translate($value);
        } catch (\Exception) {
            return $value;
        }
    });
}

Route::get('/', function () {
    $settings = PageSetting::getForPage('home');

    $featuredIds = array_filter([
        $settings['featured_product_1'] ?? null,
        $settings['featured_product_2'] ?? null,
        $settings['featured_product_3'] ?? null,
        $settings['featured_product_4'] ?? null,
    ]);

    if (count($featuredIds) > 0) {
        // Preserve the admin-chosen order
        $featuredProducts = Product::where('active', true)
            ->with('mainImage')
            ->whereIn('id', $featuredIds)
            ->get()
            ->sortBy(fn ($p) => array_search($p->id, array_values($featuredIds)))
            ->values()
            ->toArray();
    } else {
        $featuredProducts = Product::where('active', true)->with('mainImage')->take(4)->get()->toArray();
    }

    $translatableKeys = ['hero_subtitle', 'about_text_1', 'about_text_2', 'about_text_3', 'about_text_4'];
    $settingsEn = [];
    foreach ($translatableKeys as $key) {
        if (!empty($settings[$key])) {
            $settingsEn[$key] = translateSetting('home', $key, $settings[$key]);
        }
    }

    return Inertia::render('Home', [
        'featuredProducts' => $featuredProducts,
        'featuredRecipes'  => Recipe::where('active', true)->take(12)->get()->toArray(),
        'settings'         => $settings,
        'settingsEn'       => $settingsEn,
    ]);
});

Route::get('/receptai', function () {
    return Inertia::render('Recipes', [
        'recipes' => Recipe::where('active', true)->orderBy('id')->get()->toArray(),
    ]);
});

Route::get('/produktai', function () {
    return Inertia::render('Products', [
        'products' => Product::where('active', true)->with('mainImage')->orderBy('id')->get()->toArray(),
    ]);
});

Route::get('/produktai/{id}', function ($id) {
    $categoryLabels = [
        'aksesuarai'     => 'Aksesuarai',
        'sodo-baldai'    => 'Sodo baldai',
        'grilio-anglys'  => 'Grilio anglys',
        'grilis'         => 'Grilis',
        'rukyklos'       => 'Rūkyklos',
        'virykles'       => 'Viryklės ir krosnelės',
        'nesiojaimos'    => 'Nešiojamos rūkyklos',
        'profesionalios' => 'Profesionalios rūkyklos',
        'lauzavietes'    => 'Laužavietės',
    ];

    $product = Product::where('active', true)->with(['images', 'mainImage'])->findOrFail($id);
    $data = $product->toArray();
    $data['h'] = $product->height;
    $data['w'] = $product->width;
    $data['l'] = $product->length;
    $cats = is_array($product->category) ? $product->category : [$product->category];
    $data['category_label'] = implode(', ', array_map(fn($c) => $categoryLabels[$c] ?? $c, array_filter($cats)));
    $data['category_slug']  = array_values(array_filter($cats))[0] ?? null;
    $data['images'] = $product->images->map(fn ($img) => [
        'id'      => $img->id,
        'url'     => $img->url,
        'is_main' => $img->is_main,
    ])->values()->toArray();

    return Inertia::render('Product', ['product' => $data]);
});

Route::get('/es-fondai', function () {
    $settings = PageSetting::getForPage('es-fondai');

    return Inertia::render('EsFondai', ['settings' => $settings]);
});

Route::get('/kontaktai', function () {
    $settings = PageSetting::getForPage('contact');

    return Inertia::render('Contact', ['settings' => $settings]);
});

Route::get('/receptai/{id}', function ($id) {
    $recipe = Recipe::where('active', true)->findOrFail($id);

    return Inertia::render('Recipe', ['recipe' => $recipe->toArray()]);
});

Route::get('/uzsakymas', function () {
    return Inertia::render('Checkout');
});

Route::middleware('throttle:address-search')->get('/api/address-search', function (Request $request) {
    $q       = $request->query('q', '');
    $country = strtolower($request->query('country', 'lv'));
    $type    = $request->query('type', 'address'); // 'address' or 'city'
    $city    = $request->query('city', '');
    $postal  = $request->query('postal', '');
    if (strlen($q) < 3) return response()->json([]);

    $suffix = $postal ?: $city;
    $query = ($type === 'address' && $suffix) ? "{$q}, {$suffix}" : $q;

    $response = \Illuminate\Support\Facades\Http::withHeaders([
        'User-Agent' => 'ABAS Home Smokehouse/1.0 (niksindriksons2006@gmail.com)',
    ])->timeout(5)->get('https://nominatim.openstreetmap.org/search', [
        'q'              => $query,
        'countrycodes'   => $country,
        'format'         => 'json',
        'addressdetails' => 1,
        'limit'          => 7,
    ]);

    return response()->json($response->successful() ? $response->json() : []);
});

Route::get('/api/product-names', function (Request $request) {
    $ids = array_filter(array_map('intval', explode(',', $request->query('ids', ''))));
    if (empty($ids)) return response()->json([]);
    return response()->json(
        Product::whereIn('id', $ids)->pluck('name_en', 'id')
    );
});

Route::get('/lp/stations', function () {
    $data = \Illuminate\Support\Facades\Cache::remember('lp_stations', 3600, function () {
        return \Illuminate\Support\Facades\Http::timeout(10)
            ->get('https://express.pasts.lv/stationApi/index')
            ->json();
    });
    return response()->json($data ?? []);
});

Route::get('/lp/terminals', function () {
    $data = \Illuminate\Support\Facades\Cache::remember('lp_terminals', 3600, function () {
        return \Illuminate\Support\Facades\Http::timeout(10)
            ->get('https://express.pasts.lv/terminalApi/index')
            ->json();
    });
    return response()->json($data ?? []);
});

Route::middleware('throttle:shipping-calc')->post('/shipping/calculate', function (Request $request) {
    $request->validate([
        'items'   => 'required|array|min:1',
        'items.*.id'  => 'required|integer',
        'items.*.qty' => 'required|integer|min:1',
        'country' => 'required|string|max:2',
        'method'  => 'required|in:locker,courier',
    ]);

    $BALTIC = ['LT', 'LV', 'EE'];
    $country = strtoupper($request->country);

    if ($request->method === 'locker') {
        $rate = $country === 'LV' ? 2.50 : 3.05;
        return response()->json(['cost' => $rate]);
    }

    // Courier: Baltic uses actual weight; EU uses chargeable (max of actual vs volumetric)
    $totalWeight = 0;
    $isBaltic = in_array($country, $BALTIC);

    foreach ($request->items as $item) {
        $product = Product::find($item['id']);
        if (!$product) continue;

        $qty = $item['qty'];
        $actualWeight = (float) ($product->weight ?? 5) * $qty;

        if ($isBaltic) {
            $totalWeight += $actualWeight;
        } else {
            // Volumetric weight = L×W×H / 5000 (cm → kg)
            $l = (float) ($product->length ?? 30);
            $w = (float) ($product->width  ?? 30);
            $h = (float) ($product->height ?? 30);
            $volumetric = ($l * $w * $h / 5000) * $qty;
            $totalWeight += max($actualWeight, $volumetric);
        }
    }

    // Rate tables from LP contract (excl. VAT, excl. oversized surcharge)
    $lvRates = [
        [1,    3.91],
        [5,    5.50],
        [10,   7.49],
        [20,   9.73],
        [31.5, 13.98],
        [50,  17.15],
        [PHP_INT_MAX, 17.15],
    ];
    $balticRates = [
        [1,    5.20],
        [5,    5.70],
        [10,   6.50],
        [20,   7.50],
        [31.5, 8.50],
        [50,  14.00],
        [PHP_INT_MAX, 14.00],
    ];
    $euRates = [
        [5,   14.99],
        [10,  18.99],
        [20,  24.99],
        [31.5, 32.99],
        [50,  44.99],
        [70,  59.99],
        [PHP_INT_MAX, 79.99],
    ];

    $table = match(true) {
        $country === 'LV'                    => $lvRates,
        in_array($country, ['LT', 'EE'])     => $balticRates,
        default                              => $euRates,
    };
    $cost  = end($table)[1];
    foreach ($table as [$limit, $price]) {
        if ($totalWeight <= $limit) { $cost = $price; break; }
    }

    return response()->json(['cost' => $cost, 'weight' => round($totalWeight, 2)]);
});

Route::middleware('throttle:checkout')->post('/uzsakymas', function (Request $request) {
    $data = $request->validate([
        'items'               => 'required|array|min:1',
        'items.*.id'          => 'required|integer',
        'items.*.qty'         => 'required|integer|min:1|max:99',
        'parcel_locker_id'    => 'nullable|string|max:100',
        'parcel_locker_name'  => 'nullable|string|max:255',
        'first_name'          => 'required|string|max:100',
        'last_name'           => 'required|string|max:100',
        'email'               => 'required|email|max:255',
        'phone'               => 'required|string|max:30',
        'address'             => 'required|string|max:255',
        'city'                => 'required|string|max:100',
        'postal_code'         => 'required|string|max:20',
        'country'             => 'required|string|size:2',
        'lang'                => 'nullable|in:lt,en',
        'payment_method'      => 'required|in:paysera',
    ]);

    // Recalculate prices server-side — never trust client-sent prices
    $ids      = array_column($data['items'], 'id');
    $products = Product::where('active', true)->whereIn('id', $ids)->get()->keyBy('id');

    if ($products->count() !== count(array_unique($ids))) {
        return response()->json(['message' => 'One or more products are unavailable.'], 422);
    }

    $items = [];
    $itemsTotal = 0;
    foreach ($data['items'] as $item) {
        $product = $products[$item['id']];
        $items[] = [
            'id'    => $product->id,
            'name'  => $product->name,
            'price' => (float) $product->price,
            'qty'   => (int) $item['qty'],
        ];
        $itemsTotal += $product->price * $item['qty'];
    }

    // Recalculate shipping server-side using the same logic as /shipping/calculate
    $BALTIC  = ['LT', 'LV', 'EE'];
    $country = strtoupper($data['country']);
    $method  = $data['parcel_locker_id'] ? 'locker' : 'courier';

    if ($method === 'locker') {
        $shippingCost = $country === 'LV' ? 2.50 : 3.05;
    } else {
        $totalWeight = 0;
        $isBaltic    = in_array($country, $BALTIC);
        foreach ($data['items'] as $item) {
            $product      = $products[$item['id']];
            $qty          = (int) $item['qty'];
            $actualWeight = (float) ($product->weight ?? 5) * $qty;
            if ($isBaltic) {
                $totalWeight += $actualWeight;
            } else {
                $l = (float) ($product->length ?? 30);
                $w = (float) ($product->width  ?? 30);
                $h = (float) ($product->height ?? 30);
                $totalWeight += max($actualWeight, ($l * $w * $h / 5000) * $qty);
            }
        }

        $lvRates     = [[1,3.91],[5,5.50],[10,7.49],[20,9.73],[31.5,13.98],[50,17.15],[PHP_INT_MAX,17.15]];
        $balticRates = [[1,5.20],[5,5.70],[10,6.50],[20,7.50],[31.5,8.50],[50,14.00],[PHP_INT_MAX,14.00]];
        $euRates     = [[5,14.99],[10,18.99],[20,24.99],[31.5,32.99],[50,44.99],[70,59.99],[PHP_INT_MAX,79.99]];

        $table = match(true) {
            $country === 'LV'                => $lvRates,
            in_array($country, ['LT','EE']) => $balticRates,
            default                         => $euRates,
        };
        $shippingCost = end($table)[1];
        foreach ($table as [$limit, $price]) {
            if ($totalWeight <= $limit) { $shippingCost = $price; break; }
        }
    }

    $order = Order::create([
        ...$data,
        'items'         => $items,
        'total'         => round($itemsTotal, 2),
        'shipping_cost' => $shippingCost,
    ]);

    // Build Paysera payment URL — charge items total + shipping
    $grandTotal  = $order->total + $order->shipping_cost;
    $amountCents = (int) round($grandTotal * 100);

    $params = \WebToPay::buildRequest([
        'projectid'     => (int) env('PAYSERA_PROJECT_ID'),
        'sign_password' => env('PAYSERA_SIGN_PASSWORD'),
        'orderid'       => $order->id,
        'amount'        => $amountCents,
        'currency'      => 'EUR',
        'country'       => strtoupper($order->country),
        'accepturl'     => env('APP_URL') . '/uzsakymas/sekmingai/' . $order->id,
        'cancelurl'     => env('APP_URL') . '/uzsakymas/atsaukta',
        'callbackurl'   => env('APP_URL') . '/paysera/callback',
        'test'          => env('PAYSERA_TEST', false) ? 1 : 0,
        'p_firstname'   => $order->first_name,
        'p_lastname'    => $order->last_name,
        'p_email'       => $order->email,
        'p_phone'       => $order->phone,
        'p_street'      => $order->address,
        'p_city'        => $order->city,
        'p_zip'         => $order->postal_code,
        'p_countrycode' => strtoupper($order->country),
        'lang'          => 'LIT',
    ]);

    $payseraUrl = \WebToPay::getPaymentUrl('LIT') . '?' . http_build_query($params);

    return response()->json(['redirect' => $payseraUrl]);
});

// Paysera callback — called server-to-server when payment is confirmed
Route::post('/paysera/callback', function (Request $request) {
    try {
        $response = \WebToPay::validateAndParseData(
            $request->all(),
            (int) env('PAYSERA_PROJECT_ID'),
            env('PAYSERA_SIGN_PASSWORD')
        );

        $orderId = $response['orderid'] ?? null;
        $status  = $response['status']  ?? null;
        \Illuminate\Support\Facades\Log::info("Paysera callback: order={$orderId} status={$status}");

        if ($status == 1) {
            $order = Order::find($orderId);
            if ($order && $order->status === 'pending') {
                $order->update([
                    'status'     => 'paid',
                    'payment_id' => $response['payment'] ?? null,
                ]);
                app('shipping')->registerShipment($order);
                Mail::to($order->email)->send(new OrderConfirmed($order));
                Mail::to(env('ADMIN_EMAIL'))->send(new OrderPlaced($order));
                \Illuminate\Support\Facades\Log::info("Order {$order->id} paid, emails sent");
            }
        }

        return response('OK', 200);
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Paysera callback error: ' . $e->getMessage());
        return response('Error', 400);
    }
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class]);

// Success page after payment — order processing happens only via Paysera callback
Route::get('/uzsakymas/sekmingai/{id}', function ($id) {
    $order = Order::findOrFail($id);
    return Inertia::render('OrderSuccess', ['order' => $order->only('id', 'first_name', 'email', 'total', 'status')]);
});

// Cancel page
Route::get('/privatuma-politika', function () {
    return Inertia::render('PrivacyPolicy');
});

Route::get('/uzsakymas/atsaukta', function () {
    return Inertia::render('OrderCancelled');
});

// GDPR data deletion request
Route::middleware('throttle:gdpr-delete')->post('/gdpr/delete', function (Request $request) {
    $data = $request->validate([
        'email'    => 'required|email|max:255',
        'order_id' => 'required|integer',
    ]);

    // Verify the requester owns at least one order with this email + order ID combo
    $verified = Order::where('email', $data['email'])
        ->where('id', $data['order_id'])
        ->where('anonymized', false)
        ->exists();

    if (!$verified) {
        // Return the same response whether the order exists or not (prevents enumeration)
        return response()->json(['message' => 'ok']);
    }

    $count = Order::where('email', $data['email'])
        ->where('anonymized', false)
        ->update([
            'first_name'  => 'Deleted',
            'last_name'   => 'User',
            'email'       => 'deleted@deleted.invalid',
            'phone'       => '000',
            'address'     => 'Deleted',
            'city'        => 'Deleted',
            'postal_code' => '000',
            'anonymized'  => true,
        ]);

    \Illuminate\Support\Facades\Log::info("GDPR deletion: {$count} orders anonymized for {$data['email']}");

    Mail::to($data['email'])->send(new DataDeleted($count));

    return response()->json(['message' => 'ok']);
});
