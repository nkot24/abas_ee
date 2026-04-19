<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Wrap existing string values into JSON arrays before changing column type
        DB::statement("UPDATE products SET category = JSON_ARRAY(category) WHERE category IS NOT NULL AND category != ''");

        Schema::table('products', function (Blueprint $table) {
            $table->json('category')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Extract first element back to a plain string
        DB::statement("UPDATE products SET category = JSON_UNQUOTE(JSON_EXTRACT(category, '$[0]')) WHERE category IS NOT NULL");

        Schema::table('products', function (Blueprint $table) {
            $table->string('category')->nullable()->change();
        });
    }
};
