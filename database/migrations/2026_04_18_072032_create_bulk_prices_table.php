<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bulk_prices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            $table->integer('min_qty'); // start from this qty
            $table->decimal('price', 10, 2); // price per unit

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bulk_prices');
    }
};