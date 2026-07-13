<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Orders';
    protected static ?string $pluralModelLabel = 'Orders';
    protected static ?string $modelLabel = 'Order';
    protected static string|\UnitEnum|null $navigationGroup = 'Content';
    protected static ?int $navigationSort = 0;

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Section::make('Order')->schema([
                Select::make('status')
                    ->options([
                        'pending'   => 'Pending',
                        'paid'      => 'Paid',
                        'shipped'   => 'Shipped',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),

                TextInput::make('tracking_number')
                    ->label('Tracking number')
                    ->maxLength(255)
                    ->nullable(),
            ])->columns(2),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Customer')->schema([
                Grid::make(2)->schema([
                    TextEntry::make('first_name')->label('First name'),
                    TextEntry::make('last_name')->label('Last name'),
                    TextEntry::make('email')->label('Email'),
                    TextEntry::make('phone')->label('Phone'),
                    TextEntry::make('address')->label('Address'),
                    TextEntry::make('city')->label('City'),
                    TextEntry::make('postal_code')->label('Postal code'),
                    TextEntry::make('country')->label('Country'),
                ]),
            ]),

            Section::make('Order')->schema([
                Grid::make(3)->schema([
                    TextEntry::make('status')->badge(),
                    TextEntry::make('payment_method')->label('Payment method'),
                    TextEntry::make('payment_id')->label('Payment ID')->default('—'),
                    TextEntry::make('total')->label('Total')->suffix(' €'),
                    TextEntry::make('shipping_cost')->label('Shipping')->suffix(' €')->default('—'),
                    TextEntry::make('tracking_number')->label('Tracking number')->default('—'),
                    TextEntry::make('parcel_locker_name')->label('Parcel locker')->default('—'),
                    TextEntry::make('created_at')->label('Placed at')->dateTime(),
                ]),
            ]),

            Section::make('Items')->schema([
                RepeatableEntry::make('items')
                    ->label('')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('name')->label('Product'),
                            TextEntry::make('qty')->label('Qty'),
                            TextEntry::make('price')->label('Price')->suffix(' €'),
                        ]),
                    ]),
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

                TextColumn::make('first_name')
                    ->label('Customer')
                    ->searchable()
                    ->formatStateUsing(fn($record) => trim($record->first_name . ' ' . $record->last_name)),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('total')
                    ->label('Total')
                    ->suffix(' €')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid'      => 'success',
                        'pending'   => 'warning',
                        'shipped'   => 'info',
                        'cancelled' => 'danger',
                        default     => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->label('Payment')
                    ->default('—'),

                TextColumn::make('tracking_number')
                    ->label('Tracking')
                    ->default('—'),

                TextColumn::make('created_at')
                    ->label('Placed at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending'   => 'Pending',
                        'paid'      => 'Paid',
                        'shipped'   => 'Shipped',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view'  => Pages\ViewOrder::route('/{record}'),
            'edit'  => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
