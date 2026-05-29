<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductImage;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;
use ZipArchive;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    public bool $showFinnish = false;
    public array $finnishNames = [];

    public function toggleFinnish(): void
    {
        $this->showFinnish = !$this->showFinnish;

        if ($this->showFinnish && empty($this->finnishNames)) {
            $products = Product::pluck('name', 'id');
            $ids = $products->keys()->all();
            $names = $products->values()->all();
            $combined = implode("\n", $names);
            try {
                $tr = new GoogleTranslate('fi');
                $tr->setSource('et');
                $translated = $tr->translate($combined);
                $parts = explode("\n", $translated);
                foreach ($ids as $i => $id) {
                    $this->finnishNames[$id] = trim($parts[$i] ?? $names[$i]);
                }
            } catch (\Exception) {
                foreach ($products as $id => $name) {
                    $this->finnishNames[$id] = $name;
                }
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('toggleFinnish')
                ->label($this->showFinnish ? 'Show Estonian names' : 'Translate to Finnish')
                ->icon('heroicon-o-language')
                ->color($this->showFinnish ? 'warning' : 'gray')
                ->action(fn() => $this->toggleFinnish()),

            Action::make('export')
                ->label('Export ZIP')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    $products = Product::with('images')->get();

                    $exportDir = storage_path('app/exports');
                    if (!is_dir($exportDir)) {
                        mkdir($exportDir, 0755, true);
                    }

                    // Write CSV to a temp file first
                    $csvPath = tempnam(sys_get_temp_dir(), 'abas_csv_');
                    $handle  = fopen($csvPath, 'w');
                    fputcsv($handle, [
                        'name', 'name_en', 'description', 'description_en',
                        'price', 'category', 'badge', 'material',
                        'height', 'length', 'width', 'thickness', 'weight',
                        'active', 'locker_eligible', 'on_sale', 'sale_price', 'sale_percent',
                        'images',
                    ]);

                    foreach ($products as $p) {
                        $sorted = $p->images->sort(function ($a, $b) {
                            if ($a->is_main !== $b->is_main) return $b->is_main <=> $a->is_main;
                            return $a->sort_order <=> $b->sort_order;
                        })->values();

                        fputcsv($handle, [
                            $p->name,
                            $p->name_en ?? '',
                            $p->description ?? '',
                            $p->description_en ?? '',
                            $p->price,
                            implode('|', is_array($p->category) ? $p->category : []),
                            $p->badge ?? '',
                            $p->material ?? '',
                            $p->height ?? '',
                            $p->length ?? '',
                            $p->width ?? '',
                            $p->thickness ?? '',
                            $p->weight ?? '',
                            $p->active ? '1' : '0',
                            $p->locker_eligible ? '1' : '0',
                            $p->on_sale ? '1' : '0',
                            $p->sale_price ?? '',
                            $p->sale_percent ?? '',
                            $sorted->map(fn($i) => basename($i->path))->join('|'),
                        ]);
                    }
                    fclose($handle);

                    // Build ZIP
                    $zipPath = $exportDir . '/products-export.zip';
                    $zip     = new ZipArchive();
                    $opened  = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

                    if ($opened !== true) {
                        unlink($csvPath);
                        Notification::make()->title("Failed to create ZIP (code: {$opened})")->danger()->send();
                        return;
                    }

                    $zip->addFile($csvPath, 'products.csv');

                    foreach ($products as $p) {
                        foreach ($p->images as $img) {
                            $abs = storage_path('app/public/' . $img->path);
                            if (file_exists($abs)) {
                                $zip->addFile($abs, 'images/' . basename($img->path));
                            }
                        }
                    }

                    $zip->close();
                    unlink($csvPath);

                    $url = \Illuminate\Support\Facades\URL::signedRoute('products.export.download');
                    $this->redirect($url);
                }),

            Action::make('import')
                ->label('Import ZIP')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('gray')
                ->schema([
                    FileUpload::make('zip_file')
                        ->label('ZIP file (exported from this system)')
                        ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed', 'application/octet-stream'])
                        ->disk('local')
                        ->directory('imports')
                        ->required(),
                    Select::make('target_lang')
                        ->label('Store language (names/descriptions will be translated to this)')
                        ->options([
                            'lt' => 'Lithuanian (no translation needed)',
                            'lv' => 'Latvian',
                            'et' => 'Estonian',
                            'pl' => 'Polish',
                            'de' => 'German',
                            'fi' => 'Finnish',
                        ])
                        ->default('lt')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $targetLang = $data['target_lang'];

                    // Filament/Livewire may store the path relative to the local disk root
                    // or with a livewire-tmp prefix — try both locations.
                    $relative = $data['zip_file'];
                    $zipPath  = storage_path('app/' . $relative);
                    if (!file_exists($zipPath)) {
                        $zipPath = storage_path('app/private/' . $relative);
                    }
                    if (!file_exists($zipPath)) {
                        Notification::make()->title('Upload file not found: ' . $relative)->danger()->send();
                        return;
                    }

                    $extractDir = storage_path('app/imports/extract_' . uniqid());

                    $zip    = new ZipArchive();
                    $opened = $zip->open($zipPath);
                    if ($opened !== true) {
                        Notification::make()->title("Failed to open ZIP (code: {$opened})")->danger()->send();
                        return;
                    }
                    $zip->extractTo($extractDir);
                    $zip->close();

                    // Read all rows first so we can batch-translate names
                    $handle  = fopen($extractDir . '/products.csv', 'r');
                    $headers = fgetcsv($handle);
                    $rows    = [];
                    while (($row = fgetcsv($handle)) !== false) {
                        if (count($row) === count($headers)) {
                            $rows[] = array_combine($headers, $row);
                        }
                    }
                    fclose($handle);

                    // Translate names in one batch API call (names are single-line, safe to join with \n)
                    $translatedNames = [];
                    if ($targetLang !== 'lt' && !empty($rows)) {
                        try {
                            $tr   = new GoogleTranslate($targetLang);
                            $tr->setSource('en');
                            $src  = array_map(fn($r) => $r['name_en'] !== '' ? $r['name_en'] : $r['name'], $rows);
                            $out  = explode("\n", $tr->translate(implode("\n", $src)));
                            foreach ($rows as $i => $r) {
                                $translatedNames[$i] = trim($out[$i] ?? $src[$i]);
                            }
                        } catch (\Exception) {}
                    }

                    $created = 0;
                    foreach ($rows as $i => $r) {
                        if ($targetLang === 'lt') {
                            // Source store is Lithuanian — use columns as-is
                            $name    = $r['name'];
                            $nameEn  = $r['name_en'] !== '' ? $r['name_en'] : null;
                            $desc    = $r['description'] !== '' ? $r['description'] : null;
                            $descEn  = $r['description_en'] !== '' ? $r['description_en'] : null;
                        } else {
                            // Translate from English to target language
                            $name   = $translatedNames[$i] ?? ($r['name_en'] ?: $r['name']);
                            $nameEn = $r['name_en'] !== '' ? $r['name_en'] : null;

                            // Descriptions may be multi-line — translate individually
                            $descEn = $r['description_en'] !== '' ? $r['description_en'] : null;
                            $desc   = null;
                            if ($descEn) {
                                try {
                                    $tr   = new GoogleTranslate($targetLang);
                                    $tr->setSource('en');
                                    $desc = $tr->translate($descEn);
                                } catch (\Exception) {
                                    $desc = $descEn;
                                }
                            }
                        }

                        // Bypass the model's auto-translate event — we already have all translations
                        $product = Product::withoutEvents(function () use ($r, $name, $nameEn, $desc, $descEn) {
                            return Product::create([
                                'name'            => $name,
                                'name_en'         => $nameEn,
                                'description'     => $desc,
                                'description_en'  => $descEn,
                                'price'           => (float) $r['price'],
                                'category'        => $r['category'] !== '' ? explode('|', $r['category']) : [],
                                'badge'           => $r['badge'] !== '' ? $r['badge'] : null,
                                'material'        => $r['material'] !== '' ? $r['material'] : null,
                                'height'          => $r['height'] !== '' ? (int) $r['height'] : null,
                                'length'          => $r['length'] !== '' ? (int) $r['length'] : null,
                                'width'           => $r['width'] !== '' ? (int) $r['width'] : null,
                                'thickness'       => $r['thickness'] !== '' ? (float) $r['thickness'] : null,
                                'weight'          => $r['weight'] !== '' ? (float) $r['weight'] : null,
                                'active'          => (bool) $r['active'],
                                'locker_eligible' => (bool) $r['locker_eligible'],
                                'on_sale'         => (bool) $r['on_sale'],
                                'sale_price'      => $r['sale_price'] !== '' ? (float) $r['sale_price'] : null,
                                'sale_percent'    => $r['sale_percent'] !== '' ? (int) $r['sale_percent'] : null,
                            ]);
                        });

                        if (!empty($r['images'])) {
                            foreach (explode('|', $r['images']) as $idx => $filename) {
                                $src = $extractDir . '/images/' . $filename;
                                if (!file_exists($src)) continue;
                                $ext  = pathinfo($filename, PATHINFO_EXTENSION);
                                $dest = 'products/' . Str::ulid() . '.' . $ext;
                                Storage::disk('public')->put($dest, file_get_contents($src));
                                ProductImage::create([
                                    'product_id' => $product->id,
                                    'path'       => $dest,
                                    'is_main'    => $idx === 0,
                                    'sort_order' => $idx,
                                ]);
                            }
                        }

                        $created++;
                    }

                    Storage::disk('local')->deleteDirectory('imports/' . basename($extractDir));
                    Storage::disk('local')->delete($data['zip_file']);

                    Notification::make()
                        ->title("Imported {$created} products with images")
                        ->success()
                        ->send();
                }),

            CreateAction::make()->label('Add product'),
        ];
    }
}
