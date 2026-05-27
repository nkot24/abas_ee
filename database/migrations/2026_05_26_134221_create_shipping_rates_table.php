<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->string('country_group'); // lv | baltic | eu
            $table->string('method');        // locker | courier
            $table->decimal('weight_up_to', 8, 2)->nullable(); // null for locker flat rate; 9999 = unlimited
            $table->decimal('price', 8, 2);
            $table->unsignedSmallInteger('sort')->default(0);
        });

        // Seed default rates (current LP contract prices)
        DB::table('shipping_rates')->insert([
            // Locker flat rates
            ['country_group' => 'lv',     'method' => 'locker',  'weight_up_to' => null,  'price' => 2.50,  'sort' => 0],
            ['country_group' => 'baltic', 'method' => 'locker',  'weight_up_to' => null,  'price' => 3.05,  'sort' => 0],

            // Courier — Latvia
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

            // Courier — Baltic (LT, EE) — no weight limit
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_rates');
    }
};
