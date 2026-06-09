<?php

namespace App\Filament\Resources\RecipeResource\Pages;

use App\Filament\Resources\RecipeResource;
use App\Models\Recipe;
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

class ListRecipes extends ListRecords
{
    protected static string $resource = RecipeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Export ZIP')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    $recipes = Recipe::all();

                    $exportDir = storage_path('app/exports');
                    if (!is_dir($exportDir)) {
                        mkdir($exportDir, 0755, true);
                    }

                    $csvPath = tempnam(sys_get_temp_dir(), 'abas_recipes_csv_');
                    $handle  = fopen($csvPath, 'w');
                    fputcsv($handle, [
                        'title', 'title_en', 'category', 'active',
                        'ingredients', 'ingredients_en',
                        'steps', 'steps_en',
                        'image',
                    ]);

                    foreach ($recipes as $r) {
                        fputcsv($handle, [
                            $r->title,
                            $r->title_en ?? '',
                            $r->category ?? '',
                            $r->active ? '1' : '0',
                            json_encode($r->ingredients ?? []),
                            json_encode($r->ingredients_en ?? []),
                            json_encode($r->steps ?? []),
                            json_encode($r->steps_en ?? []),
                            $r->image ? basename($r->image) : '',
                        ]);
                    }
                    fclose($handle);

                    $zipPath = $exportDir . '/recipes-export.zip';
                    $zip     = new ZipArchive();
                    $opened  = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

                    if ($opened !== true) {
                        unlink($csvPath);
                        Notification::make()->title("Failed to create ZIP (code: {$opened})")->danger()->send();
                        return;
                    }

                    $zip->addFile($csvPath, 'recipes.csv');

                    foreach ($recipes as $r) {
                        if ($r->image) {
                            $abs = storage_path('app/public/' . $r->image);
                            if (file_exists($abs)) {
                                $zip->addFile($abs, 'images/' . basename($r->image));
                            }
                        }
                    }

                    $zip->close();
                    unlink($csvPath);

                    $url = \Illuminate\Support\Facades\URL::signedRoute('recipes.export.download');
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
                        ->label('Store language (titles/content will be translated to this)')
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

                    $handle  = fopen($extractDir . '/recipes.csv', 'r');
                    $headers = fgetcsv($handle);
                    $rows    = [];
                    while (($row = fgetcsv($handle)) !== false) {
                        if (count($row) === count($headers)) {
                            $rows[] = array_combine($headers, $row);
                        }
                    }
                    fclose($handle);

                    $translatedTitles = [];
                    if ($targetLang !== 'lt' && !empty($rows)) {
                        try {
                            $tr  = new GoogleTranslate($targetLang);
                            $tr->setSource('en');
                            $src = array_map(fn($r) => $r['title_en'] !== '' ? $r['title_en'] : $r['title'], $rows);
                            $out = explode("\n", $tr->translate(implode("\n", $src)));
                            foreach ($rows as $i => $r) {
                                $translatedTitles[$i] = trim($out[$i] ?? $src[$i]);
                            }
                        } catch (\Exception) {}
                    }

                    $created = 0;
                    foreach ($rows as $i => $r) {
                        if ($targetLang === 'lt') {
                            $title          = $r['title'];
                            $titleEn        = $r['title_en'] !== '' ? $r['title_en'] : null;
                            $ingredients    = json_decode($r['ingredients'], true) ?? [];
                            $ingredientsEn  = $r['ingredients_en'] !== '' ? json_decode($r['ingredients_en'], true) : null;
                            $steps          = json_decode($r['steps'], true) ?? [];
                            $stepsEn        = $r['steps_en'] !== '' ? json_decode($r['steps_en'], true) : null;
                        } else {
                            $title   = $translatedTitles[$i] ?? ($r['title_en'] ?: $r['title']);
                            $titleEn = $r['title_en'] !== '' ? $r['title_en'] : null;

                            $ingredientsEnRaw = $r['ingredients_en'] !== '' ? json_decode($r['ingredients_en'], true) : null;
                            $stepsEnRaw       = $r['steps_en'] !== '' ? json_decode($r['steps_en'], true) : null;

                            $ingredientsEn = $ingredientsEnRaw;
                            $stepsEn       = $stepsEnRaw;

                            $ingredients = [];
                            if ($ingredientsEnRaw) {
                                try {
                                    $tr = new GoogleTranslate($targetLang);
                                    $tr->setSource('en');
                                    foreach ($ingredientsEnRaw as $ing) {
                                        $ingredients[] = ['item' => $tr->translate($ing['item'])];
                                    }
                                } catch (\Exception) {
                                    $ingredients = $ingredientsEnRaw;
                                }
                            }

                            $steps = [];
                            if ($stepsEnRaw) {
                                try {
                                    $tr = new GoogleTranslate($targetLang);
                                    $tr->setSource('en');
                                    foreach ($stepsEnRaw as $step) {
                                        $steps[] = ['step' => $tr->translate($step['step'])];
                                    }
                                } catch (\Exception) {
                                    $steps = $stepsEnRaw;
                                }
                            }
                        }

                        $recipe = Recipe::withoutEvents(function () use ($r, $title, $titleEn, $ingredients, $ingredientsEn, $steps, $stepsEn) {
                            return Recipe::create([
                                'title'          => $title,
                                'title_en'       => $titleEn,
                                'category'       => $r['category'] !== '' ? $r['category'] : null,
                                'active'         => (bool) $r['active'],
                                'ingredients'    => $ingredients,
                                'ingredients_en' => $ingredientsEn,
                                'steps'          => $steps,
                                'steps_en'       => $stepsEn,
                            ]);
                        });

                        if (!empty($r['image'])) {
                            $src = $extractDir . '/images/' . $r['image'];
                            if (file_exists($src)) {
                                $ext  = pathinfo($r['image'], PATHINFO_EXTENSION);
                                $dest = 'recipes/' . Str::ulid() . '.' . $ext;
                                Storage::disk('public')->put($dest, file_get_contents($src));
                                $recipe->updateQuietly(['image' => $dest]);
                            }
                        }

                        $created++;
                    }

                    Storage::disk('local')->deleteDirectory('imports/' . basename($extractDir));
                    Storage::disk('local')->delete($data['zip_file']);

                    Notification::make()
                        ->title("Imported {$created} recipes")
                        ->success()
                        ->send();
                }),

            CreateAction::make()->label('Add recipe'),
        ];
    }
}
