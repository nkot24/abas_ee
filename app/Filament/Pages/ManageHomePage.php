<?php

namespace App\Filament\Pages;

use App\Models\PageSetting;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageHomePage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Homepage';
    protected static ?string $title = 'Homepage';
    protected static string|\UnitEnum|null $navigationGroup = 'Pages';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.pages.manage-home-page';

    public ?array $data = [];

    public function mount(): void
    {
        $defaults = [
            'hero_subtitle'      => 'Tradiciniai dūminiai gaminiai iš aukščiausios kokybės ingredientų. Paragaukite tikrojo skonio.',
            'about_text_1'       => 'Mes esame kūpinimo ir grilių gamybos įmonė iš Latvijos, pavadinimu SIA „Linda-1". Mūsų verslas – namų, sodo ir viešojo maitinimo įrangos projektavimas ir gamyba – mėsos, paukštienos, žuvies, sūrio ir daržovių rūkymui bei griliavimuisi. Savo produkciją parduodame su registruotu prekių ženklu ABAS.',
            'about_text_2'       => 'Mūsų produktai yra unikalūs tarp kitų, galbūt panašių gaminių. Visų pirma, mūsų rūkyklos yra šiluminiu būdu izoliuotos, kas užtikrina energijos efektyvumą ir saugumą. Rūkymo metu mūsų produktai sunaudoja labai mažai malkų ir išskiria itin mažai šilumos nuo rūkyklos paviršiaus.',
            'about_text_3'       => 'Unikalus ir patentuotas dizainas suteikia ABAS Smokehouse naudotojams visišką temperatūros lygio ir proceso stabilumo kontrolę – oro srauto valdymas ir tiesioginis temperatūros ekranas užtikrina, kad visi rūkyti patiekalai bus skanūs ir kokybiški.',
            'about_text_4'       => 'Visi mūsų produktai skirti naudoti tik lauke; jie yra ilgaamžiai įvairių oro sąlygų atžvilgiu ir lengvai pernešami. Judumas priklauso nuo jūsų pasirinkto modelio.',
            'featured_product_1' => null,
            'featured_product_2' => null,
            'featured_product_3' => null,
            'featured_product_4' => null,
        ];

        $saved = PageSetting::getForPage('home');

        $this->form->fill(array_merge($defaults, $saved));
    }

    public function form(Schema $form): Schema
    {
        $productOptions = Product::where('active', true)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();

        return $form
            ->schema([
                Section::make('Featured products')
                    ->description('Choose the 4 products shown in the homepage product section.')
                    ->schema([
                        Select::make('featured_product_1')
                            ->label('Product slot 1')
                            ->options($productOptions)
                            ->searchable()
                            ->nullable(),
                        Select::make('featured_product_2')
                            ->label('Product slot 2')
                            ->options($productOptions)
                            ->searchable()
                            ->nullable(),
                        Select::make('featured_product_3')
                            ->label('Product slot 3')
                            ->options($productOptions)
                            ->searchable()
                            ->nullable(),
                        Select::make('featured_product_4')
                            ->label('Product slot 4')
                            ->options($productOptions)
                            ->searchable()
                            ->nullable(),
                    ])->columns(2),

                Section::make('Hero section')
                    ->description('Text shown below the ABAS SMOKE HOUSE heading')
                    ->schema([
                        Textarea::make('hero_subtitle')
                            ->label('Main description')
                            ->rows(3)
                            ->required(),
                    ]),

                Section::make('"About us" section')
                    ->schema([
                        Textarea::make('about_text_1')
                            ->label('Paragraph 1')
                            ->rows(4),
                        Textarea::make('about_text_2')
                            ->label('Paragraph 2')
                            ->rows(4),
                        Textarea::make('about_text_3')
                            ->label('Paragraph 3')
                            ->rows(4),
                        Textarea::make('about_text_4')
                            ->label('Paragraph 4')
                            ->rows(4),
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
            PageSetting::set('home', $key, $value);
        }

        Notification::make()
            ->success()
            ->title('Changes saved!')
            ->send();
    }
}
