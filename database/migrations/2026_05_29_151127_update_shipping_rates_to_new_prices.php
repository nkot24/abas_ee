<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('shipping_rates')->truncate();

        DB::table('shipping_rates')->insert([
            // Locker flat rates
            ['country_group' => 'lv',     'method' => 'locker', 'weight_up_to' => null, 'price' => 2.99, 'sort' => 0],
            ['country_group' => 'baltic', 'method' => 'locker', 'weight_up_to' => null, 'price' => 3.49, 'sort' => 0],

            // Courier — Latvia
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 1,    'price' => 4.99,  'sort' => 1],
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 5,    'price' => 7.99,  'sort' => 2],
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 10,   'price' => 9.99,  'sort' => 3],
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 20,   'price' => 11.99, 'sort' => 4],
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 30,   'price' => 14.99, 'sort' => 5],
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 40,   'price' => 19.99, 'sort' => 6],
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 50,   'price' => 19.99, 'sort' => 7],
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 60,   'price' => 26.99, 'sort' => 8],
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 80,   'price' => 29.99, 'sort' => 9],
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 100,  'price' => 29.99, 'sort' => 10],
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 150,  'price' => 34.99, 'sort' => 11],
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 9999, 'price' => 38.99, 'sort' => 12],

            // Courier — Baltic (LT, EE)
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 1,    'price' => 9.99,  'sort' => 1],
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 5,    'price' => 13.99, 'sort' => 2],
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 10,   'price' => 14.99, 'sort' => 3],
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 20,   'price' => 16.99, 'sort' => 4],
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 30,   'price' => 19.99, 'sort' => 5],
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 40,   'price' => 26.99, 'sort' => 6],
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 50,   'price' => 26.99, 'sort' => 7],
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 60,   'price' => 34.99, 'sort' => 8],
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 80,   'price' => 37.99, 'sort' => 9],
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 100,  'price' => 39.99, 'sort' => 10],
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 150,  'price' => 44.99, 'sort' => 11],
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 9999, 'price' => 49.99, 'sort' => 12],
        ]);
    }

    public function down(): void
    {
        DB::table('shipping_rates')->truncate();

        DB::table('shipping_rates')->insert([
            ['country_group' => 'lv',     'method' => 'locker', 'weight_up_to' => null, 'price' => 2.50, 'sort' => 0],
            ['country_group' => 'baltic', 'method' => 'locker', 'weight_up_to' => null, 'price' => 3.05, 'sort' => 0],

            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 1,    'price' => 3.91,  'sort' => 1],
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 5,    'price' => 5.50,  'sort' => 2],
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 10,   'price' => 7.49,  'sort' => 3],
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 20,   'price' => 9.73,  'sort' => 4],
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 30,   'price' => 13.98, 'sort' => 5],
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 40,   'price' => 17.15, 'sort' => 6],
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 50,   'price' => 17.15, 'sort' => 7],
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 60,   'price' => 17.15, 'sort' => 8],
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 80,   'price' => 17.15, 'sort' => 9],
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 100,  'price' => 17.15, 'sort' => 10],
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 150,  'price' => 17.15, 'sort' => 11],
            ['country_group' => 'lv', 'method' => 'courier', 'weight_up_to' => 9999, 'price' => 17.15, 'sort' => 12],

            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 1,    'price' => 5.20,  'sort' => 1],
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 5,    'price' => 5.70,  'sort' => 2],
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 10,   'price' => 6.50,  'sort' => 3],
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 20,   'price' => 7.50,  'sort' => 4],
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 30,   'price' => 8.50,  'sort' => 5],
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 40,   'price' => 14.00, 'sort' => 6],
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 50,   'price' => 14.00, 'sort' => 7],
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 60,   'price' => 14.00, 'sort' => 8],
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 80,   'price' => 14.00, 'sort' => 9],
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 100,  'price' => 14.00, 'sort' => 10],
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 150,  'price' => 14.00, 'sort' => 11],
            ['country_group' => 'baltic', 'method' => 'courier', 'weight_up_to' => 9999, 'price' => 14.00, 'sort' => 12],
        ]);
    }
};
