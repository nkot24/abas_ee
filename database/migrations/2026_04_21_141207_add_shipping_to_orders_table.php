<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('shipping_cost', 8, 2)->default(0)->after('total');
            $table->string('parcel_locker_id')->nullable()->after('shipping_cost');
            $table->string('parcel_locker_name')->nullable()->after('parcel_locker_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_cost', 'parcel_locker_id', 'parcel_locker_name']);
        });
    }
};
