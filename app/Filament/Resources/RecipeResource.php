<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecipeResource\Pages;
use App\Models\Recipe;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
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

class RecipeResource extends Resource
{
    protected static ?string $model = Recipe::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Recipes';
    protected static ?string $pluralModelLabel = 'Recipes';
    protected static ?string $modelLabel = 'Recipe';
    protected static string|\UnitEnum|null $navigationGroup = 'Content';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Section::make('Image')->schema([
                FileUpload::make('image')
                    ->label('Recipe image')
                    ->image()
                    ->disk('public')
                    ->directory('recipes')
                    ->visibility('public')
                    ->nullable()
                    ->columnSpanFull(),
            ]),

            Section::make()->schema([
                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),

                Select::make('category')
                    ->label('Category')
                    ->required()
                    ->options([
                        'Pagrindinis' => 'Main course',
                        'Užkandžiai'  => 'Snacks',
                        'Garnyras'    => 'Side dish',
                        'Salotos'     => 'Salads',
                        'Žuvis'       => 'Fish',
                        'Marinatas'   => 'Marinade',
                        'Sriubos'     => 'Soups',
                    ]),

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

                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->sortable(),

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
            'index'  => Pages\ListRecipes::route('/'),
            'create' => Pages\CreateRecipe::route('/create'),
            'edit'   => Pages\EditRecipe::route('/{record}/edit'),
        ];
    }
}
