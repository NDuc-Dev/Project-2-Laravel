<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSizesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productsizes', function (Blueprint $table) {
            $table->id('product_size_id');
            $table->foreignId('product_id')->constrained('products', 'product_id');
            $table->foreignId('size_id')->constrained('sizes', 'size_id');
            $table->decimal('unit_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productsizes');
    }
};
