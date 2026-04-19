<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\ProductImage;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->newImagePaths = $data['uploaded_images'] ?? [];
        unset($data['uploaded_images']);
        return $data;
    }

    protected function afterCreate(): void
    {
        foreach ($this->newImagePaths ?? [] as $index => $path) {
            ProductImage::create([
                'product_id' => $this->record->id,
                'path'       => $path,
                'is_main'    => $index === 0,
                'sort_order' => $index,
            ]);
        }
    }
}
