<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'ABAS Mini Rūkykla',            'price' => 149,  'category' => 'rukyklos',       'badge' => 'Populiaru', 'material' => 'nerūdijantis', 'height' => 40,  'width' => 30 ],
            ['name' => 'ABAS Standard Rūkykla',         'price' => 249,  'category' => 'rukyklos',       'badge' => null,        'material' => 'nerūdijantis', 'height' => 60,  'width' => 40 ],
            ['name' => 'ABAS Pro Rūkykla',              'price' => 399,  'category' => 'rukyklos',       'badge' => 'Naujiena',  'material' => 'nerūdijantis', 'height' => 80,  'width' => 50 ],
            ['name' => 'ABAS XL Rūkykla',               'price' => 549,  'category' => 'rukyklos',       'badge' => null,        'material' => 'nerūdijantis', 'height' => 100, 'width' => 60 ],
            ['name' => 'ABAS Mini Nešiojama Rūkykla',   'price' => 119,  'category' => 'nesiojaimos',    'badge' => null,        'material' => 'paprastas',    'height' => 35,  'width' => 25 ],
            ['name' => 'ABAS Compact Nešiojama',        'price' => 189,  'category' => 'nesiojaimos',    'badge' => 'Populiaru', 'material' => 'paprastas',    'height' => 45,  'width' => 35 ],
            ['name' => 'ABAS Pro Profesionali Rūkykla', 'price' => 699,  'category' => 'profesionalios', 'badge' => null,        'material' => 'nerūdijantis', 'height' => 120, 'width' => 70 ],
            ['name' => 'ABAS Master Profesionali',      'price' => 999,  'category' => 'profesionalios', 'badge' => 'Naujiena',  'material' => 'nerūdijantis', 'height' => 150, 'width' => 80 ],
            ['name' => 'ABAS Grilis Classic',            'price' => 199,  'category' => 'grilis',         'badge' => null,        'material' => 'paprastas',    'height' => 90,  'width' => 55 ],
            ['name' => 'ABAS Grilis Pro',                'price' => 349,  'category' => 'grilis',         'badge' => 'Populiaru', 'material' => 'nerūdijantis', 'height' => 110, 'width' => 65 ],
            ['name' => 'ABAS Grilis XL',                 'price' => 499,  'category' => 'grilis',         'badge' => null,        'material' => 'nerūdijantis', 'height' => 130, 'width' => 80 ],
            ['name' => 'BBQ Priekaba Classic',           'price' => 890,  'category' => 'bbq-priekabos',  'badge' => null,        'material' => 'paprastas',    'height' => 140, 'width' => 200],
            ['name' => 'BBQ Priekaba Pro',               'price' => 1290, 'category' => 'bbq-priekabos',  'badge' => 'Naujiena',  'material' => 'nerūdijantis', 'height' => 160, 'width' => 250],
            ['name' => 'Sodo Kėdžių Komplektas',         'price' => 249,  'category' => 'sodo-baldai',    'badge' => null,        'material' => 'paprastas',    'height' => 90,  'width' => 60 ],
            ['name' => 'Sodo Stalas su Suolais',         'price' => 349,  'category' => 'sodo-baldai',    'badge' => null,        'material' => 'paprastas',    'height' => 75,  'width' => 180],
            ['name' => 'Grilio Anglys 5 kg',             'price' => 12,   'category' => 'grilio-anglys',  'badge' => null,        'material' => null,           'height' => null,'width' => null],
            ['name' => 'Grilio Anglys 10 kg',            'price' => 22,   'category' => 'grilio-anglys',  'badge' => 'Populiaru', 'material' => null,           'height' => null,'width' => null],
            ['name' => 'Laužavietė Round',               'price' => 129,  'category' => 'lauzavietes',    'badge' => null,        'material' => 'paprastas',    'height' => 40,  'width' => 60 ],
            ['name' => 'Laužavietė XL',                  'price' => 199,  'category' => 'lauzavietes',    'badge' => null,        'material' => 'paprastas',    'height' => 50,  'width' => 80 ],
            ['name' => 'Viryklė Lauko',                  'price' => 159,  'category' => 'virykles',        'badge' => null,        'material' => 'paprastas',    'height' => 60,  'width' => 50 ],
            ['name' => 'Krosnelė Lauko Pro',             'price' => 279,  'category' => 'virykles',        'badge' => 'Naujiena',  'material' => 'nerūdijantis', 'height' => 80,  'width' => 55 ],
            ['name' => 'Pirštinės BBQ',                  'price' => 16,   'category' => 'aksesuarai',     'badge' => null,        'material' => null,           'height' => null,'width' => null],
            ['name' => 'ABAS prijuostė',                 'price' => 19,   'category' => 'aksesuarai',     'badge' => null,        'material' => null,           'height' => null,'width' => null],
            ['name' => 'Mėsos termometras',              'price' => 29,   'category' => 'aksesuarai',     'badge' => 'Naujiena',  'material' => null,           'height' => null,'width' => null],
            ['name' => 'Rūkymo drožlių rinkinys',        'price' => 18,   'category' => 'kiti',           'badge' => null,        'material' => null,           'height' => null,'width' => null],
            ['name' => 'Temperatūros zondas',            'price' => 24,   'category' => 'kiti',           'badge' => null,        'material' => null,           'height' => null,'width' => null],
        ];

        foreach ($products as $product) {
            Product::create(array_merge($product, ['active' => true]));
        }
    }
}
