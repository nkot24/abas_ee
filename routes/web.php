<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
});

Route::get('/receptai', function () {
    return Inertia::render('Recipes');
});

Route::get('/produktai', function () {
    return Inertia::render('Products');
});

Route::get('/produktai/{id}', function ($id) {
    $categoryLabels = [
        'aksesuarai'     => 'Aksesuarai',
        'bbq-priekabos'  => 'BBQ priekabos',
        'sodo-baldai'    => 'Sodo baldai',
        'grilio-anglys'  => 'Grilio anglys',
        'grilis'         => 'Grilis',
        'rukyklos'       => 'Rūkyklos',
        'kiti'           => 'Kiti produktai',
        'virykles'       => 'Viryklės ir krosnelės',
        'nesiojaimos'    => 'Nešiojamos rūkyklos',
        'profesionalios' => 'Profesionalios rūkyklos',
        'lauzavietes'    => 'Laužavietės',
    ];

    $products = [
        1  => ['id'=>1,  'name'=>'ABAS Mini Rūkykla',            'price'=>149,  'category'=>'rukyklos',       'badge'=>'Populiaru', 'material'=>'nerūdijantis', 'h'=>40,  'w'=>30 ],
        2  => ['id'=>2,  'name'=>'ABAS Standard Rūkykla',         'price'=>249,  'category'=>'rukyklos',       'badge'=>null,        'material'=>'nerūdijantis', 'h'=>60,  'w'=>40 ],
        3  => ['id'=>3,  'name'=>'ABAS Pro Rūkykla',              'price'=>399,  'category'=>'rukyklos',       'badge'=>'Naujiena',  'material'=>'nerūdijantis', 'h'=>80,  'w'=>50 ],
        4  => ['id'=>4,  'name'=>'ABAS XL Rūkykla',               'price'=>549,  'category'=>'rukyklos',       'badge'=>null,        'material'=>'nerūdijantis', 'h'=>100, 'w'=>60 ],
        5  => ['id'=>5,  'name'=>'ABAS Mini Nešiojama Rūkykla',   'price'=>119,  'category'=>'nesiojaimos',    'badge'=>null,        'material'=>'paprastas',    'h'=>35,  'w'=>25 ],
        6  => ['id'=>6,  'name'=>'ABAS Compact Nešiojama',        'price'=>189,  'category'=>'nesiojaimos',    'badge'=>'Populiaru', 'material'=>'paprastas',    'h'=>45,  'w'=>35 ],
        7  => ['id'=>7,  'name'=>'ABAS Pro Profesionali Rūkykla', 'price'=>699,  'category'=>'profesionalios', 'badge'=>null,        'material'=>'nerūdijantis', 'h'=>120, 'w'=>70 ],
        8  => ['id'=>8,  'name'=>'ABAS Master Profesionali',      'price'=>999,  'category'=>'profesionalios', 'badge'=>'Naujiena',  'material'=>'nerūdijantis', 'h'=>150, 'w'=>80 ],
        9  => ['id'=>9,  'name'=>'ABAS Grilis Classic',            'price'=>199,  'category'=>'grilis',         'badge'=>null,        'material'=>'paprastas',    'h'=>90,  'w'=>55 ],
        10 => ['id'=>10, 'name'=>'ABAS Grilis Pro',                'price'=>349,  'category'=>'grilis',         'badge'=>'Populiaru', 'material'=>'nerūdijantis', 'h'=>110, 'w'=>65 ],
        11 => ['id'=>11, 'name'=>'ABAS Grilis XL',                 'price'=>499,  'category'=>'grilis',         'badge'=>null,        'material'=>'nerūdijantis', 'h'=>130, 'w'=>80 ],
        12 => ['id'=>12, 'name'=>'BBQ Priekaba Classic',           'price'=>890,  'category'=>'bbq-priekabos',  'badge'=>null,        'material'=>'paprastas',    'h'=>140, 'w'=>200],
        13 => ['id'=>13, 'name'=>'BBQ Priekaba Pro',               'price'=>1290, 'category'=>'bbq-priekabos',  'badge'=>'Naujiena',  'material'=>'nerūdijantis', 'h'=>160, 'w'=>250],
        14 => ['id'=>14, 'name'=>'Sodo Kėdžių Komplektas',         'price'=>249,  'category'=>'sodo-baldai',    'badge'=>null,        'material'=>'paprastas',    'h'=>90,  'w'=>60 ],
        15 => ['id'=>15, 'name'=>'Sodo Stalas su Suolais',         'price'=>349,  'category'=>'sodo-baldai',    'badge'=>null,        'material'=>'paprastas',    'h'=>75,  'w'=>180],
        16 => ['id'=>16, 'name'=>'Grilio Anglys 5 kg',             'price'=>12,   'category'=>'grilio-anglys',  'badge'=>null,        'material'=>null,           'h'=>null,'w'=>null],
        17 => ['id'=>17, 'name'=>'Grilio Anglys 10 kg',            'price'=>22,   'category'=>'grilio-anglys',  'badge'=>'Populiaru', 'material'=>null,           'h'=>null,'w'=>null],
        18 => ['id'=>18, 'name'=>'Laužavietė Round',               'price'=>129,  'category'=>'lauzavietes',    'badge'=>null,        'material'=>'paprastas',    'h'=>40,  'w'=>60 ],
        19 => ['id'=>19, 'name'=>'Laužavietė XL',                  'price'=>199,  'category'=>'lauzavietes',    'badge'=>null,        'material'=>'paprastas',    'h'=>50,  'w'=>80 ],
        20 => ['id'=>20, 'name'=>'Viryklė Lauko',                  'price'=>159,  'category'=>'virykles',        'badge'=>null,        'material'=>'paprastas',    'h'=>60,  'w'=>50 ],
        21 => ['id'=>21, 'name'=>'Krosnelė Lauko Pro',             'price'=>279,  'category'=>'virykles',        'badge'=>'Naujiena',  'material'=>'nerūdijantis', 'h'=>80,  'w'=>55 ],
        22 => ['id'=>22, 'name'=>'Pirštinės BBQ',                  'price'=>16,   'category'=>'aksesuarai',     'badge'=>null,        'material'=>null,           'h'=>null,'w'=>null],
        23 => ['id'=>23, 'name'=>'ABAS prijuostė',                 'price'=>19,   'category'=>'aksesuarai',     'badge'=>null,        'material'=>null,           'h'=>null,'w'=>null],
        24 => ['id'=>24, 'name'=>'Mėsos termometras',              'price'=>29,   'category'=>'aksesuarai',     'badge'=>'Naujiena',  'material'=>null,           'h'=>null,'w'=>null],
        25 => ['id'=>25, 'name'=>'Rūkymo drožlių rinkinys',        'price'=>18,   'category'=>'kiti',           'badge'=>null,        'material'=>null,           'h'=>null,'w'=>null],
        26 => ['id'=>26, 'name'=>'Temperatūros zondas',            'price'=>24,   'category'=>'kiti',           'badge'=>null,        'material'=>null,           'h'=>null,'w'=>null],
    ];

    abort_if(!isset($products[$id]), 404);

    $product = $products[$id];
    $product['category_label'] = $categoryLabels[$product['category']] ?? $product['category'];

    return Inertia::render('Product', ['product' => $product]);
});

Route::get('/es-fondai', function () {
    return Inertia::render('EsFondai');
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
