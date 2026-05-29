<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    public bool $showLatvian = false;
    public array $latvianNames = [];

    public function toggleLatvian(): void
    {
        $this->showLatvian = !$this->showLatvian;

        if ($this->showLatvian && empty($this->latvianNames)) {
            $products = Product::pluck('name', 'id');
            $ids = $products->keys()->all();
            $names = $products->values()->all();
            $combined = implode("\n", $names);
            try {
                $tr = new GoogleTranslate('lv');
                $tr->setSource('lt');
                $translated = $tr->translate($combined);
                $parts = explode("\n", $translated);
                foreach ($ids as $i => $id) {
                    $this->latvianNames[$id] = trim($parts[$i] ?? $names[$i]);
                }
            } catch (\Exception) {
                foreach ($products as $id => $name) {
                    $this->latvianNames[$id] = $name;
                }
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('toggleLatvian')
                ->label($this->showLatvian ? 'Show Lithuanian names' : 'Translate to Latvian')
                ->icon('heroicon-o-language')
                ->color($this->showLatvian ? 'warning' : 'gray')
                ->action(fn() => $this->toggleLatvian()),
            CreateAction::make()->label('Add product'),
        ];
    }
}
