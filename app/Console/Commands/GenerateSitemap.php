<?php

namespace App\Console\Commands;

use App\Http\Controllers\SitemapController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class GenerateSitemap extends Command
{
    protected $signature   = 'sitemap:generate';
    protected $description = 'Warm the /sitemap.xml cache';

    public function handle(): void
    {
        $xml = SitemapController::build()->render();

        Cache::put('sitemap.xml', $xml, now()->addHour());

        $this->info('Sitemap cache warmed.');
    }
}
