<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->string('title_lt')->nullable()->after('title_en');
            $table->json('ingredients_lt')->nullable()->after('ingredients');
            $table->json('steps_lt')->nullable()->after('steps');
            $table->json('ingredients_en')->nullable()->after('ingredients_lt');
            $table->json('steps_en')->nullable()->after('steps_lt');
        });
    }

    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn(['title_lt', 'ingredients_lt', 'steps_lt', 'ingredients_en', 'steps_en']);
        });
    }
};
