<?php

use App\Mail\OrderConfirmed;
use App\Mail\OrderPlaced;
use App\Models\Order;
use App\Models\PageSetting;
use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

Route::get('/api/product-names', function (Request $request) {
    $ids = array_filter(array_map('intval', explode(',', $request->query('ids', ''))));
    if (empty($ids)) return response()->json([]);
    return response()->json(
        Product::whereIn('id', $ids)->pluck('name_en', 'id')
    );
});

Route::get('/omniva/locations', function () {
    $cacheKey = 'omniva_locations';
    $locations = \Illuminate\Support\Facades\Cache::remember($cacheKey, 3600, function () {
        $json = file_get_contents('https://www.omniva.ee/locations.json');
        $all  = json_decode($json, true);
        return array_values(array_filter($all, fn($l) =>
            in_array($l['A0_NAME'] ?? '', ['Latvija', 'Lietuva', 'Eesti']) &&
            ($l['TYPE'] ?? '') == '0'
        ));
    });
    return response()->json($locations);
});

Route::post('/shipping/calculate', function (Request $request) {
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
        $rate = in_array($country, $BALTIC) ? 3.49 : 3.49;
        return response()->json(['cost' => $rate]);
    }

    // Courier: calculate by chargeable weight (actual vs volumetric)
    $totalWeight = 0;
    $maxVolumetric = 0;

    foreach ($request->items as $item) {
        $product = Product::find($item['id']);
        if (!$product) continue;

        $qty = $item['qty'];
        $actualWeight = (float) ($product->weight ?? 1) * $qty;

        // Volumetric weight = L×W×H / 5000 (cm → kg)
        $l = (float) ($product->length ?? 30);
        $w = (float) ($product->width  ?? 30);
        $h = (float) ($product->height ?? 30);
        $volumetric = ($l * $w * $h / 5000) * $qty;

        $totalWeight += max($actualWeight, $volumetric);
    }

    // Rate tables
    $balticRates = [
        [5,   6.99],
        [10,  8.99],
        [20, 12.99],
        [31.5, 16.99],
        [50, 24.99],
        [70, 34.99],
        [PHP_INT_MAX, 49.99],
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

    $table = in_array($country, $BALTIC) ? $balticRates : $euRates;
    $cost  = end($table)[1];
    foreach ($table as [$limit, $price]) {
        if ($totalWeight <= $limit) { $cost = $price; break; }
    }

    return response()->json(['cost' => $cost, 'weight' => round($totalWeight, 2)]);
});

Route::post('/uzsakymas', function (Request $request) {
    $data = $request->validate([
        'items'               => 'required|array|min:1',
        'items.*.id'          => 'required|integer',
        'items.*.name'        => 'required|string',
        'items.*.price'       => 'required|numeric|min:0',
        'items.*.qty'         => 'required|integer|min:1',
        'total'               => 'required|numeric|min:0',
        'shipping_cost'       => 'required|numeric|min:0',
        'parcel_locker_id'    => 'nullable|string',
        'parcel_locker_name'  => 'nullable|string',
        'first_name'          => 'required|string|max:100',
        'last_name'           => 'required|string|max:100',
        'email'               => 'required|email|max:255',
        'phone'               => 'required|string|max:30',
        'address'             => 'required|string|max:255',
        'city'                => 'required|string|max:100',
        'postal_code'         => 'required|string|max:20',
        'country'             => 'required|string|max:2',
        'lang'                => 'nullable|in:lt,en',
        'payment_method'      => 'required|in:paysera',
    ]);

    $order = Order::create($data);

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
    \Illuminate\Support\Facades\Log::info('Paysera callback received', $request->all());
    try {
        $response = \WebToPay::validateAndParseData(
            $request->all(),
            (int) env('PAYSERA_PROJECT_ID'),
            env('PAYSERA_SIGN_PASSWORD')
        );

        \Illuminate\Support\Facades\Log::info('Paysera validated', $response);

        if ($response['status'] == 1) {
            $order = Order::find($response['orderid']);
            if ($order && $order->status === 'pending') {
                $order->update([
                    'status'     => 'paid',
                    'payment_id' => $response['payment'] ?? null,
                ]);
                Mail::to($order->email)->send(new OrderConfirmed($order));
                Mail::to(env('ADMIN_EMAIL', 'niksindriksons2006@gmail.com'))->send(new OrderPlaced($order));
                \Illuminate\Support\Facades\Log::info('Emails sent for order ' . $order->id);
            }
        }

        return response('OK', 200);
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Paysera callback error: ' . $e->getMessage());
        return response('Error: ' . $e->getMessage(), 400);
    }
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class]);

// Success page after payment
Route::get('/uzsakymas/sekmingai/{id}', function ($id) {
    $order = Order::findOrFail($id);

    if ($order->status === 'pending') {
        $order->update(['status' => 'paid']);
        Mail::to($order->email)->send(new OrderConfirmed($order));
        Mail::to(env('ADMIN_EMAIL', 'niksindriksons2006@gmail.com'))->send(new OrderPlaced($order));
    }

    return Inertia::render('OrderSuccess', ['order' => $order->only('id', 'first_name', 'email', 'total')]);
});

// Cancel page
Route::get('/uzsakymas/atsaukta', function () {
    return Inertia::render('OrderCancelled');
});
