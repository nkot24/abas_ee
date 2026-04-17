<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name'  => 'Admin',
            'email' => 'admin@abas.lt',
        ]);

        $this->call([
            ProductSeeder::class,
            RecipeSeeder::class,
        ]);
    }
}
