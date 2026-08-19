<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_first_name')->nullable()->after('note');
            $table->string('shipping_last_name')->nullable()->after('shipping_first_name');
            $table->string('shipping_phone')->nullable()->after('shipping_last_name');
            $table->string('shipping_address1')->nullable()->after('shipping_phone');
            $table->string('shipping_address2')->nullable()->after('shipping_address1');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_first_name',
                'shipping_last_name',
                'shipping_phone',
                'shipping_address1',
                'shipping_address2',
            ]);
        });
    }
};
