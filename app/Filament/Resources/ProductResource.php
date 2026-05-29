<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
    protected static ?string $navigationLabel = 'Produktai';
    protected static ?string $pluralModelLabel = 'Produktai';
    protected static ?string $modelLabel = 'Produktas';
    protected static string|\UnitEnum|null $navigationGroup = 'Turinys';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Section::make('Images')
                ->description('Select multiple images at once. Drag to reorder — the first image is used as the main image in the product list.')
                ->schema([
                    FileUpload::make('uploaded_images')
                        ->label('')
                        ->multiple()
                        ->image()
                        ->disk('public')
                        ->directory('products')
                        ->visibility('public')
                        ->reorderable()
                        ->fetchFileInformation(false)
                        ->columnSpanFull(),
                ]),

            Section::make('Basic information')->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('Description')
                    ->rows(4)
                    ->nullable()
                    ->columnSpanFull(),

                TextInput::make('price')
                    ->label('Price (€)')
                    ->numeric()
                    ->required()
                    ->minValue(0),

                Select::make('category')
                    ->label('Categories')
                    ->multiple()
                    ->required()
                    ->options([
                        'aksesuarai'     => 'Accessories',
                        'sodo-baldai'    => 'Garden furniture',
                        'grilis'         => 'Grill',
                        'rukyklos'       => 'Smokers',
                        'grilio-anglys'  => 'Grill charcoal',
                        'virykles'       => 'Stoves & cookers',
                        'nesiojaimos'    => 'Portable smokers',
                        'profesionalios' => 'Professional smokers',
                        'lauzavietes'    => 'Fire pits',
                    ]),

                TextInput::make('badge')
                    ->label('Badge (e.g. Popular, New)')
                    ->maxLength(50)
                    ->nullable(),

                Toggle::make('on_sale')
                    ->label('On sale')
                    ->default(false)
                    ->live()
                    ->columnSpanFull(),

                TextInput::make('sale_price')
                    ->label('Sale price (€)')
                    ->numeric()
                    ->minValue(0)
                    ->nullable()
                    ->hidden(fn ($get) => !$get('on_sale')),

                TextInput::make('sale_percent')
                    ->label('Discount (%)')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(99)
                    ->nullable()
                    ->suffix('%')
                    ->hidden(fn ($get) => !$get('on_sale')),
            ])->columns(2),

            Section::make('Specifications')->schema([
                Select::make('material')
                    ->label('Material')
                    ->options([
                        'nerūdijantis' => 'Stainless steel',
                        'paprastas'    => 'Regular steel',
                        'corten'       => 'Corten steel',
                    ])
                    ->nullable(),

                TextInput::make('height')
                    ->label('Height (cm)')
                    ->numeric()
                    ->minValue(0)
                    ->nullable(),

                TextInput::make('length')
                    ->label('Length (cm)')
                    ->numeric()
                    ->minValue(0)
                    ->nullable(),

                TextInput::make('width')
                    ->label('Width (cm)')
                    ->numeric()
                    ->minValue(0)
                    ->nullable(),

                TextInput::make('thickness')
                    ->label('Material thickness (mm)')
                    ->numeric()
                    ->minValue(0)
                    ->nullable(),

                TextInput::make('weight')
                    ->label('Weight (kg)')
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
                    ->sortable()
                    ->getStateUsing(fn($record, $livewire) =>
                        ($livewire->showFinnish && isset($livewire->finnishNames[$record->id]))
                            ? $livewire->finnishNames[$record->id]
                            : $record->name
                    ),

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

                IconColumn::make('on_sale')
                    ->label('Sale')
                    ->boolean(),

                TextColumn::make('sale_price')
                    ->label('Sale price')
                    ->suffix(' €')
                    ->default('—'),
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
