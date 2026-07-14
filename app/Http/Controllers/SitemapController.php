<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Support\Facades\Cache;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index()
    {
        $xml = Cache::remember('sitemap.xml', now()->addHour(), fn () => static::build()->render());

        return response($xml, 200)->header('Content-Type', 'text/xml');
    }

    public static function build(): Sitemap
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/')->setPriority(1.0))
            ->add(Url::create('/products')->setPriority(0.8))
            ->add(Url::create('/recipes')->setPriority(0.8))
            ->add(Url::create('/contact')->setPriority(0.5))
            ->add(Url::create('/eu-funds')->setPriority(0.5))
            ->add(Url::create('/privacy-policy')->setPriority(0.3))
            ->add(Url::create('/shipping')->setPriority(0.3));

        Product::where('active', true)
            ->select('id', 'updated_at')
            ->orderBy('id')
            ->each(function (Product $product) use ($sitemap) {
                $sitemap->add(
                    Url::create("/products/{$product->id}")
                        ->setLastModificationDate($product->updated_at)
                        ->setPriority(0.6)
                );
            });

        Recipe::where('active', true)
            ->select('id', 'updated_at')
            ->orderBy('id')
            ->each(function (Recipe $recipe) use ($sitemap) {
                $sitemap->add(
                    Url::create("/recipes/{$recipe->id}")
                        ->setLastModificationDate($recipe->updated_at)
                        ->setPriority(0.6)
                );
            });

        return $sitemap;
    }
}
