<?php

namespace App\Filament\Pages;

use App\Models\PageSetting;
use Filament\Actions\Action;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageContactPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-phone';
    protected static ?string $navigationLabel = 'Contact';
    protected static ?string $title = 'Contact page';
    protected static string|\UnitEnum|null $navigationGroup = 'Pages';
    protected static ?int $navigationSort = 2;
    protected string $view = 'filament.pages.manage-contact-page';

    public ?array $data = [];

    public function mount(): void
    {
        $defaults = [
            'phone'               => '+371 29284179',
            'email'               => 'info@abas.lv',
            'company_name'        => 'SIA Linda-1',
            'reg_number'          => 'LV40003167227',
            'legal_address'       => 'Kandava, Sabiles g. 2',
            'bank_account_1'      => 'LV05UNLA0011003467703',
            'bank_code_1'         => 'UNLALV2X',
            'bank_name_1'         => 'A/S SEB banka',
            'bank_account_2'      => 'LV79HABA0551005509068',
            'bank_code_2'         => 'HABALV2X',
            'bank_name_2'         => 'AS Swedbank',
            'office_address'      => 'Kandava, Sabiles g. 2',
            'production_address'  => 'Kandava, Jelgavas g. 1i',
            'production_phone'    => '+371 29284179',
            'production_contact'  => 'Guntars',
            'showroom_address'    => '„Lidostas parks 5", „Vismaņi", Mārupes pag., Mārupes nov., LV-2167',
            'showroom_phone'      => '+371 20383017',
            'map_embed_url'       => 'https://maps.google.com/maps?q=Jelgavas+iela+1d,+Kandava,+LV-3120,+Latvia&t=&z=15&ie=UTF8&iwloc=&output=embed',
        ];

        $saved = PageSetting::getForPage('contact');

        $this->form->fill(array_merge($defaults, $saved));
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Contact information')
                    ->schema([
                        TextInput::make('phone')
                            ->label('Phone number')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required(),
                    ])->columns(2),

                Section::make('Company details')
                    ->schema([
                        TextInput::make('company_name')
                            ->label('Company name')
                            ->required(),
                        TextInput::make('reg_number')
                            ->label('Registration No.')
                            ->required(),
                        TextInput::make('legal_address')
                            ->label('Legal address')
                            ->required(),
                    ])->columns(2),

                Section::make('Bank accounts')
                    ->schema([
                        TextInput::make('bank_account_1')->label('Account No. 1'),
                        TextInput::make('bank_code_1')->label('Bank code No. 1'),
                        TextInput::make('bank_name_1')->label('Bank No. 1'),
                        TextInput::make('bank_account_2')->label('Account No. 2'),
                        TextInput::make('bank_code_2')->label('Bank code No. 2'),
                        TextInput::make('bank_name_2')->label('Bank No. 2'),
                    ])->columns(3),

                Section::make('Addresses')
                    ->schema([
                        TextInput::make('office_address')
                            ->label('Office address'),
                        TextInput::make('production_address')
                            ->label('Production & warehouse address'),
                        TextInput::make('production_phone')
                            ->label('Production phone'),
                        TextInput::make('production_contact')
                            ->label('Contact person'),
                        Textarea::make('showroom_address')
                            ->label('Showroom address')
                            ->rows(2),
                        TextInput::make('showroom_phone')
                            ->label('Showroom phone'),
                    ])->columns(2),

                Section::make('Map')
                    ->schema([
                        TextInput::make('map_embed_url')
                            ->label('Google Maps embed URL')
                            ->url()
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            PageSetting::set('contact', $key, $value);
        }

        Notification::make()
            ->success()
            ->title('Changes saved!')
            ->send();
    }
}
