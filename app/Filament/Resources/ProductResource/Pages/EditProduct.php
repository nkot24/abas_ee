<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\ProductImage;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['uploaded_images'] = $this->record
            ->images()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->pluck('path')
            ->toArray();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->newImagePaths = $data['uploaded_images'] ?? [];
        unset($data['uploaded_images']);
        return $data;
    }

    protected function afterSave(): void
    {
        $paths = $this->newImagePaths ?? [];

        $this->record->images()->delete();

        foreach ($paths as $index => $path) {
            ProductImage::create([
                'product_id' => $this->record->id,
                'path'       => $path,
                'is_main'    => $index === 0,
                'sort_order' => $index,
            ]);
        }
    }
}
