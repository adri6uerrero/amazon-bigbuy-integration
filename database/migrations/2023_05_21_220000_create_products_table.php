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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('sku')->unique();
            $table->string('amazon_asin')->nullable();
            $table->string('bigbuy_id')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('amazon_price', 10, 2);
            $table->decimal('bigbuy_price', 10, 2);
            $table->integer('stock')->default(0);
            $table->integer('amazon_stock')->default(0);
            $table->integer('bigbuy_stock')->default(0);
            $table->string('status')->default('active');
            $table->string('category');
            $table->string('image_url')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->json('dimensions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
