<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecipeResource\Pages;
use App\Models\Recipe;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
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

class RecipeResource extends Resource
{
    protected static ?string $model = Recipe::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Receptai';
    protected static ?string $pluralModelLabel = 'Receptai';
    protected static ?string $modelLabel = 'Receptas';
    protected static string|\UnitEnum|null $navigationGroup = 'Turinys';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Section::make('Nuotrauka')->schema([
                FileUpload::make('image')
                    ->label('Recepto nuotrauka')
                    ->image()
                    ->disk('public')
                    ->directory('recipes')
                    ->visibility('public')
                    ->nullable()
                    ->columnSpanFull(),
            ]),

            Section::make()->schema([
                TextInput::make('title')
                    ->label('Pavadinimas')
                    ->required()
                    ->maxLength(255),

                Select::make('category')
                    ->label('Kategorija')
                    ->required()
                    ->options([
                        'Pagrindinis' => 'Pagrindinis patiekalas',
                        'Užkandžiai'  => 'Užkandžiai',
                        'Garnyras'    => 'Garnyras',
                        'Salotos'     => 'Salotos',
                        'Žuvis'       => 'Žuvis',
                        'Marinatas'   => 'Marinatas',
                        'Sriubos'     => 'Sriubos',
                    ]),

                Toggle::make('active')
                    ->label('Rodomas svetainėje')
                    ->default(true),
            ])->columns(2),

            Section::make('Ingredientai')->schema([
                Repeater::make('ingredients')
                    ->schema([
                        TextInput::make('item')
                            ->label('Ingredientas')
                            ->required(),
                    ])
                    ->addActionLabel('Pridėti ingredientą')
                    ->columnSpanFull(),
            ]),

            Section::make('Gaminimo žingsniai')->schema([
                Repeater::make('steps')
                    ->schema([
                        Textarea::make('step')
                            ->label('Žingsnis')
                            ->required()
                            ->rows(2),
                    ])
                    ->addActionLabel('Pridėti žingsnį')
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Pavadinimas')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->label('Kategorija')
                    ->badge()
                    ->sortable(),

                IconColumn::make('active')
                    ->label('Aktyvus')
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
            'index'  => Pages\ListRecipes::route('/'),
            'create' => Pages\CreateRecipe::route('/create'),
            'edit'   => Pages\EditRecipe::route('/{record}/edit'),
        ];
    }
}
