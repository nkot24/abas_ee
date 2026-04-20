<?php

use App\Models\PageSetting;
use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

    return Inertia::render('Home', [
        'featuredProducts' => $featuredProducts,
        'featuredRecipes'  => Recipe::where('active', true)->take(12)->get()->toArray(),
        'settings'         => $settings,
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
