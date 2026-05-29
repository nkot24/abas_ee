<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Console\Command;
use Stichoza\GoogleTranslate\GoogleTranslate;

class AutoTranslate extends Command
{
    protected $signature   = 'translate:all {--force : Re-translate even if EN already exists}';
    protected $description = 'Auto-translate product names/descriptions and recipe titles to English';

    public function handle(): void
    {
        $tr = new GoogleTranslate('en');
        $tr->setSource('et');

        $this->translateProducts($tr);
        $this->translateRecipes($tr);

        $this->info('Done!');
    }

    private function translateProducts(GoogleTranslate $tr): void
    {
        $query = Product::query();
        if (!$this->option('force')) {
            $query->whereNull('name_en');
        }

        $products = $query->get();
        $this->info("Translating {$products->count()} products...");

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        foreach ($products as $product) {
            try {
                $product->name_en        = $tr->translate($product->name);
                $product->description_en = $product->description ? $tr->translate($product->description) : null;
                $product->saveQuietly();
            } catch (\Exception $e) {
                $this->newLine();
                $this->warn("Failed product #{$product->id}: {$e->getMessage()}");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    private function translateRecipes(GoogleTranslate $tr): void
    {
        $query = Recipe::query();
        if (!$this->option('force')) {
            $query->whereNull('title_en');
        }

        $recipes = $query->get();
        $this->info("Translating {$recipes->count()} recipes...");

        $bar = $this->output->createProgressBar($recipes->count());
        $bar->start();

        foreach ($recipes as $recipe) {
            try {
                $recipe->title_en = $tr->translate($recipe->title);
                $recipe->saveQuietly();
            } catch (\Exception $e) {
                $this->newLine();
                $this->warn("Failed recipe #{$recipe->id}: {$e->getMessage()}");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }
}
