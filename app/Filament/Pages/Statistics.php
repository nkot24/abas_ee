<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\Product;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Statistics extends Page
{
    protected static string|\UnitEnum|null      $navigationGroup = null;
    protected static \BackedEnum|string|null    $navigationIcon  = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Statistics';
    protected static ?string $title          = 'Order Statistics';
    protected static ?int    $navigationSort = 10;
    protected string $view = 'filament.pages.statistics';

    // Summary stats
    public int    $totalOrders        = 0;
    public float  $totalRevenue       = 0;
    public int    $monthOrders        = 0;
    public float  $monthRevenue       = 0;
    public int    $totalCustomers     = 0;

    // Visit stats
    public int    $totalVisits        = 0;
    public int    $todayVisits        = 0;
    public int    $monthVisits        = 0;
    public array  $visitsTrend        = []; // last 14 days

    // Products ranked by orders
    public array $productStats = [];
    public int   $maxOrders    = 1;

    public function mount(): void
    {
        $paid = Order::where('status', 'paid');

        $this->totalOrders    = (clone $paid)->count();
        $this->totalRevenue   = (clone $paid)->sum(DB::raw('total + shipping_cost'));
        $this->monthOrders    = (clone $paid)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $this->monthRevenue   = (clone $paid)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum(DB::raw('total + shipping_cost'));
        $this->totalCustomers = (clone $paid)->whereNot('email', 'deleted@deleted.invalid')->distinct('email')->count('email');

        // Visit stats
        $this->totalVisits  = (int) DB::table('page_views')->sum('count');
        $this->todayVisits  = (int) DB::table('page_views')->where('date', now()->toDateString())->value('count');
        $this->monthVisits  = (int) DB::table('page_views')
            ->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->sum('count');

        $rows = DB::table('page_views')
            ->where('date', '>=', now()->subDays(13)->toDateString())
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $trend = [];
        for ($i = 13; $i >= 0; $i--) {
            $d = now()->subDays($i)->toDateString();
            $trend[] = ['date' => $d, 'count' => (int) ($rows[$d] ?? 0)];
        }
        $this->visitsTrend = $trend;

        $this->productStats = $this->buildProductStats();
        $this->maxOrders    = max(1, (int) ($this->productStats[0]['orders'] ?? 1));
    }

    private function buildProductStats(): array
    {
        // Count orders and qty per product ID from JSON items column
        $orders = Order::where('status', 'paid')->get(['items']);

        $counts = []; // [id => ['orders' => n, 'qty' => n, 'revenue' => n, 'name' => '...']]
        foreach ($orders as $order) {
            foreach ((array) $order->items as $item) {
                $id = (int) ($item['id'] ?? 0);
                if (!$id) continue;
                if (!isset($counts[$id])) {
                    $counts[$id] = ['orders' => 0, 'qty' => 0, 'revenue' => 0.0, 'name' => $item['name'] ?? ''];
                }
                $counts[$id]['orders']++;
                $counts[$id]['qty']     += (int)   ($item['qty']   ?? 1);
                $counts[$id]['revenue'] += (float) ($item['price'] ?? 0) * (int) ($item['qty'] ?? 1);
            }
        }

        // Merge with current product data for accurate name/price/active status
        $products = Product::whereIn('id', array_keys($counts))->get()->keyBy('id');

        // Also include products with zero orders
        $allProducts = Product::all();
        foreach ($allProducts as $p) {
            if (!isset($counts[$p->id])) {
                $counts[$p->id] = ['orders' => 0, 'qty' => 0, 'revenue' => 0.0, 'name' => $p->name];
            }
        }

        $rows = [];
        foreach ($counts as $id => $stat) {
            $product = $products[$id] ?? null;
            $rows[] = [
                'id'      => $id,
                'name'    => $product?->name ?? $stat['name'],
                'price'   => $product ? (float) $product->price : null,
                'active'  => $product?->active ?? false,
                'exists'  => $product !== null,
                'orders'  => $stat['orders'],
                'qty'     => $stat['qty'],
                'revenue' => round($stat['revenue'], 2),
            ];
        }

        usort($rows, fn($a, $b) => $b['orders'] <=> $a['orders']);

        return $rows;
    }
}
