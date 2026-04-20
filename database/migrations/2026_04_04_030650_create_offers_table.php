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
       Schema::create('offers', function (Blueprint $table) {
    $table->id();

    $table->string('title');
    $table->text('description')->nullable();

    $table->decimal('min_amount', 10, 2); // target

    $table->string('reward_type'); // discount / gift
    $table->decimal('reward_value', 10, 2)->nullable();

    $table->date('start_date');
    $table->date('end_date');

    $table->boolean('status')->default(1);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
