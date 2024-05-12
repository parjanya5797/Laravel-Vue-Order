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

        Schema::create('line_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('name');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('variation_id')->nullable();
            $table->integer('quantity');
            $table->string('tax_class')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('subtotal_tax', 10, 2);
            $table->decimal('total', 10, 2);
            $table->decimal('total_tax', 10, 2);
            $table->string('sku');
            $table->decimal('price', 10, 2);
            $table->json('image_src')->nullable();
            $table->string('parent_name')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->index('order_id');
            $table->index('product_id');
            $table->index('variation_id');
            $table->index('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('line_items');
    }
};
