<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
});

Route::get('/receptai', function () {
    return Inertia::render('Recipes');
});

Route::get('/kontaktai', function () {
    return Inertia::render('Contact');
});

Route::get('/receptai/{id}', function ($id) {
    $recipes = [
        1  => ['id' => 1,  'title' => 'Kepsniai griliuje su marinatu',                        'category' => 'Pagrindinis'],
        2  => ['id' => 2,  'title' => 'Šakočio lašalas',                                      'category' => 'Užkandžiai'],
        3  => ['id' => 3,  'title' => 'Griliuoti pipirniai rūkytų prieskonių marinade',       'category' => 'Garnyras'],
        4  => ['id' => 4,  'title' => 'Burgeris su karamelizuotais svogūnais ir sūriu',       'category' => 'Pagrindinis'],
        5  => ['id' => 5,  'title' => 'Karštų marinuotų žaliosiose',                          'category' => 'Salotos'],
        6  => ['id' => 6,  'title' => 'Griliuotos vištos marinuotos plunksnos',               'category' => 'Pagrindinis'],
        7  => ['id' => 7,  'title' => 'Rūkytos vištos šlaunelės',                             'category' => 'Pagrindinis'],
        8  => ['id' => 8,  'title' => 'Citrinų-česnakų marinuoti krevetai',                   'category' => 'Užkandžiai'],
        9  => ['id' => 9,  'title' => 'Burgeris su karamelizuotais svogūnais ir sūriu',       'category' => 'Pagrindinis'],
        10 => ['id' => 10, 'title' => 'Griliuotos vištos marinuotos plunksnos',               'category' => 'Pagrindinis'],
        11 => ['id' => 11, 'title' => 'Rūkytos vištos šlaunelės',                             'category' => 'Pagrindinis'],
        12 => ['id' => 12, 'title' => 'Citrinų-česnakų marinuoti krevetai',                   'category' => 'Užkandžiai'],
        13 => ['id' => 13, 'title' => 'Urea koja marinuota',                                  'category' => 'Pagrindinis'],
        14 => ['id' => 14, 'title' => 'Šonkauliukai marinade su čili padažu',                 'category' => 'Pagrindinis'],
        15 => ['id' => 15, 'title' => 'Klasikinė marinuota mėsa su svogūnais',                'category' => 'Pagrindinis'],
        16 => ['id' => 16, 'title' => 'Žuvis',                                                'category' => 'Žuvis'],
        17 => ['id' => 17, 'title' => 'Citrinų marinatas – puiku galijai su višta',           'category' => 'Marinatas'],
    ];

    abort_if(!isset($recipes[$id]), 404);

    return Inertia::render('Recipe', ['recipe' => $recipes[$id]]);
});
