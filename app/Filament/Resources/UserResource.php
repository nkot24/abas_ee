<?php
namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Users';
    protected static ?string $pluralModelLabel = 'Users';
    protected static ?string $modelLabel = 'User';
    protected static ?int $navigationSort = 99;

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Section::make()->schema([
                TextInput::make('name')->label('Name')->required()->maxLength(255),
                TextInput::make('email')->label('Email')->email()->required()->unique(ignoreRecord: true)->maxLength(255),
                TextInput::make('password')
                    ->label('Password')->password()->revealable()
                    ->required(fn (string $operation) => $operation === 'create')
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->dehydrateStateUsing(fn (string $state) => bcrypt($state))
                    ->helperText('Leave blank to keep current password'),
                Toggle::make('is_admin')->label('Admin access')->default(false),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Name')->searchable()->sortable(),
                TextColumn::make('email')->label('Email')->searchable()->sortable(),
                IconColumn::make('is_admin')->label('Admin')->boolean(),
                TextColumn::make('created_at')->label('Created')->dateTime('Y-m-d H:i')->sortable(),
            ])
            ->filters([])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->groupedBulkActions([DeleteBulkAction::make()])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
