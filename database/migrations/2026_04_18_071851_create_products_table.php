<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            // 🔥 CORE WHOLESALE FIELDS
            $table->enum('type', ['egg', 'hen']); // product type
            $table->enum('sale_type', ['tray', 'piece', 'weight']); // how it sells

            $table->decimal('base_price', 10, 2); // current market rate

            $table->integer('stock')->default(0);

            $table->text('description')->nullable();
            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
