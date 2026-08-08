<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {

            // Old column remove
            $table->dropColumn('min_amount');

            // New columns
            $table->enum('condition_type', ['price', 'qty', 'kg', 'piece'])
                  ->after('description');

            $table->decimal('condition_value', 10, 2)
                  ->after('condition_type');

            $table->enum('reward_unit', ['piece', 'kg', 'price'])
                  ->nullable()
                  ->after('reward_value');
        });
    }

    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {

            $table->dropColumn([
                'condition_type',
                'condition_value',
                'reward_unit'
            ]);

            $table->decimal('min_amount', 10, 2)
                  ->after('description');
        });
    }
};