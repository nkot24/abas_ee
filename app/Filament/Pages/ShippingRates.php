<?php

namespace App\Filament\Pages;

use App\Models\ShippingRate;
use App\Services\ShippingCalculator;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ShippingRates extends Page
{
    protected static string|\UnitEnum|null      $navigationGroup = null;
    protected static \BackedEnum|string|null    $navigationIcon  = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Shipping Rates';
    protected static ?string $title          = 'Shipping Rates';
    protected static ?int    $navigationSort = 11;
    protected string $view = 'filament.pages.shipping-rates';

    public float  $lockerLv     = 2.50;
    public float  $lockerBaltic = 3.05;
    public array  $lvRates      = [];
    public array  $balticRates  = [];

    public function mount(): void
    {
        $this->loadRates();
    }

    private function loadRates(): void
    {
        $all = ShippingRate::orderBy('sort')->get();

        $locker = $all->where('method', 'locker');
        $this->lockerLv     = (float) ($locker->where('country_group', 'lv')->first()?->price     ?? 2.50);
        $this->lockerBaltic = (float) ($locker->where('country_group', 'baltic')->first()?->price  ?? 3.05);

        $toArray = fn($rows) => $rows->map(fn($r) => [
            'id'           => $r->id,
            'weight_up_to' => (float) $r->weight_up_to,
            'price'        => (float) $r->price,
        ])->values()->toArray();

        $courier = $all->where('method', 'courier');
        $this->lvRates     = $toArray($courier->where('country_group', 'lv'));
        $this->balticRates = $toArray($courier->where('country_group', 'baltic'));
    }

    public function save(): void
    {
        // Locker flat rates
        ShippingRate::where('country_group', 'lv')->where('method', 'locker')
            ->update(['price' => max(0, (float) $this->lockerLv)]);
        ShippingRate::where('country_group', 'baltic')->where('method', 'locker')
            ->update(['price' => max(0, (float) $this->lockerBaltic)]);

        // Courier weight-bracket rates
        foreach (array_merge($this->lvRates, $this->balticRates) as $rate) {
            ShippingRate::where('id', (int) $rate['id'])->update([
                'weight_up_to' => (float) $rate['weight_up_to'],
                'price'        => max(0, (float) $rate['price']),
            ]);
        }

        ShippingCalculator::clearCache();

        Notification::make()
            ->title('Shipping rates saved successfully')
            ->success()
            ->send();
    }
}
