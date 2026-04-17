<?php

namespace Database\Seeders;

use App\Models\Recipe;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        $recipes = [
            ['title' => 'Kepsniai griliuje su marinatu',                        'category' => 'Pagrindinis'],
            ['title' => 'Šakočio lašalas',                                      'category' => 'Užkandžiai'],
            ['title' => 'Griliuoti pipirniai rūkytų prieskonių marinade',       'category' => 'Garnyras'],
            ['title' => 'Burgeris su karamelizuotais svogūnais ir sūriu',       'category' => 'Pagrindinis'],
            ['title' => 'Karštų marinuotų žaliosiose',                          'category' => 'Salotos'],
            ['title' => 'Griliuotos vištos marinuotos plunksnos',               'category' => 'Pagrindinis'],
            ['title' => 'Rūkytos vištos šlaunelės',                             'category' => 'Pagrindinis'],
            ['title' => 'Citrinų-česnakų marinuoti krevetai',                   'category' => 'Užkandžiai'],
            ['title' => 'Burgeris su karamelizuotais svogūnais ir sūriu',       'category' => 'Pagrindinis'],
            ['title' => 'Griliuotos vištos marinuotos plunksnos',               'category' => 'Pagrindinis'],
            ['title' => 'Rūkytos vištos šlaunelės',                             'category' => 'Pagrindinis'],
            ['title' => 'Citrinų-česnakų marinuoti krevetai',                   'category' => 'Užkandžiai'],
            ['title' => 'Urea koja marinuota',                                  'category' => 'Pagrindinis'],
            ['title' => 'Šonkauliukai marinade su čili padažu',                 'category' => 'Pagrindinis'],
            ['title' => 'Klasikinė marinuota mėsa su svogūnais',                'category' => 'Pagrindinis'],
            ['title' => 'Žuvis',                                                'category' => 'Žuvis'],
            ['title' => 'Citrinų marinatas – puiku galijai su višta',           'category' => 'Marinatas'],
        ];

        foreach ($recipes as $recipe) {
            Recipe::create(array_merge($recipe, ['active' => true]));
        }
    }
}
