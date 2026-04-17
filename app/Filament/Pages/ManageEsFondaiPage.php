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

class ManageEsFondaiPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-flag';
    protected static ?string $navigationLabel = 'EU Funds';
    protected static ?string $title = 'EU Funds page';
    protected static string|\UnitEnum|null $navigationGroup = 'Pages';
    protected static ?int $navigationSort = 3;
    protected string $view = 'filament.pages.manage-es-fondai-page';

    public ?array $data = [];

    public function mount(): void
    {
        $defaults = [
            'section1_text'  => 'SIA „Linda-1" 2016 m. gegužės 2 d. pasirašė sutartį Nr. SKV-L-2016/193 su Latvijos investicijų ir plėtros agentūra dėl paramos gavimo pagal priemonę „Tarptautinio konkurencingumo skatinimas", kurią bendrai finansuoja Europos regioninės plėtros fondas.',
            'section2_p1'    => '2020 m. liepos 1 d. SIA „Linda-1", bendradarbiaudama su LVMI „Silava", pradėjo įgyvendinti programos „Augimas ir užimtumas" specifinio paramos tikslo 1.1.1. „Padidinti Latvijos mokslo institucijų mokslinius ir inovacinius pajėgumus bei gebėjimą pritraukti išorinį finansavimą, investuojant į žmogiškuosius išteklius ir infrastruktūrą" priemonės 1.1.1.1. „Taikomieji tyrimai" projektą „Naujos maisto rūkymo technologijos sukūrimas, skirtas policiklinių aromatinių angliavandenilių (benzpireno) koncentracijos mažinimui rūkytuose mėsos produktuose – „Blue smoke" (Nr. 1.1.1.1/19/A/092).',
            'section2_p2'    => 'Tyrimo tikslas – įgyti intelektinės nuosavybės teises ir ekonominių pranašumų, sukuriant naujausiais mokslo pasiekimais pagrįstą naują maisto rūkymo technologiją, kurią sudarys automatizuoto valdymo rūkymo įrenginys ir specialiai jam sukurtas kuras, kurie kompleksiškai užtikrins, kad maisto produktų rūkymo procese naudojamos, natūralaus medienos degimo rezultate gautos dumos būtų gaminamos ir kontroliuojamos taip, kad rūkymo produkcija atitiktų EB nustatytus kriterijus dėl benzpireno koncentracijos, kartu išsaugant galutinio produkto maistinę vertę ir organoleptines savybes.',
            'project_budget'   => '617 503,51 EUR',
            'project_deadline' => 'iki 2022 m. gruodžio 31 d.',
        ];

        $saved = PageSetting::getForPage('es-fondai');

        $this->form->fill(array_merge($defaults, $saved));
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('EU funds support')
                    ->schema([
                        Textarea::make('section1_text')
                            ->label('Text paragraph')
                            ->rows(5)
                            ->required(),
                    ]),

                Section::make('Research project')
                    ->schema([
                        Textarea::make('section2_p1')
                            ->label('Paragraph 1')
                            ->rows(5),
                        Textarea::make('section2_p2')
                            ->label('Paragraph 2')
                            ->rows(5),
                        TextInput::make('project_budget')
                            ->label('Project funding'),
                        TextInput::make('project_deadline')
                            ->label('Project deadline'),
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
            PageSetting::set('es-fondai', $key, $value);
        }

        Notification::make()
            ->success()
            ->title('Changes saved!')
            ->send();
    }
}
