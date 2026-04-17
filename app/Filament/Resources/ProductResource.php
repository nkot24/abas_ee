<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Products';
    protected static ?string $pluralModelLabel = 'Products';
    protected static ?string $modelLabel = 'Product';
    protected static string|\UnitEnum|null $navigationGroup = 'Content';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Section::make('Images')->schema([
                Repeater::make('images')
                    ->relationship('images')
                    ->schema([
                        FileUpload::make('path')
                            ->label('Image')
                            ->image()
                            ->disk('public')
                            ->directory('products')
                            ->visibility('public')
                            ->required()
                            ->columnSpan(2),

                        Toggle::make('is_main')
                            ->label('Main image (shown in product list)')
                            ->default(false)
                            ->live()
                            ->afterStateUpdated(function (bool $state, $component, $livewire) {
                                if (! $state) {
                                    return;
                                }
                                // Find the current item's key from its state path
                                $parts      = explode('.', $component->getStatePath());
                                $currentKey = $parts[count($parts) - 2];

                                // Unset is_main on every other item
                                $images = data_get($livewire->data, 'images', []);
                                foreach (array_keys($images) as $key) {
                                    if ($key !== $currentKey) {
                                        data_set($livewire->data, "images.{$key}.is_main", false);
                                    }
                                }
                            })
                            ->columnSpan(1),
                    ])
                    ->columns(3)
                    ->addActionLabel('Add image')
                    ->reorderable('sort_order')
                    ->collapsible()
                    ->columnSpanFull(),
            ]),

            Section::make('Basic information')->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('price')
                    ->label('Price (€)')
                    ->numeric()
                    ->required()
                    ->minValue(0),

                Select::make('category')
                    ->label('Category')
                    ->required()
                    ->options([
                        'aksesuarai'     => 'Accessories',
                        'bbq-priekabos'  => 'BBQ trailers',
                        'sodo-baldai'    => 'Garden furniture',
                        'grilio-anglys'  => 'Grill charcoal',
                        'grilis'         => 'Grill',
                        'rukyklos'       => 'Smokers',
                        'kiti'           => 'Other products',
                        'virykles'       => 'Stoves & cookers',
                        'nesiojaimos'    => 'Portable smokers',
                        'profesionalios' => 'Professional smokers',
                        'lauzavietes'    => 'Fire pits',
                    ]),

                TextInput::make('badge')
                    ->label('Badge (e.g. Popular, New)')
                    ->maxLength(50)
                    ->nullable(),
            ])->columns(2),

            Section::make('Specifications')->schema([
                Select::make('material')
                    ->label('Material')
                    ->options([
                        'nerūdijantis' => 'Stainless steel',
                        'paprastas'    => 'Regular steel',
                    ])
                    ->nullable(),

                TextInput::make('height')
                    ->label('Height (cm)')
                    ->numeric()
                    ->minValue(0)
                    ->nullable(),

                TextInput::make('width')
                    ->label('Width (cm)')
                    ->numeric()
                    ->minValue(0)
                    ->nullable(),

                Toggle::make('active')
                    ->label('Visible on website')
                    ->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Price')
                    ->suffix(' €')
                    ->sortable(),

                TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->sortable(),

                TextColumn::make('badge')
                    ->label('Badge')
                    ->default('—'),

                IconColumn::make('active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('id');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
