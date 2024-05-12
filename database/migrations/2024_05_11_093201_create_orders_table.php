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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('order_key')->unique();
            $table->string('status');
            $table->dateTime('date_created');
            $table->decimal('total', 10, 2);
            $table->unsignedBigInteger('customer_id');
            $table->text('customer_note')->nullable();
            $table->json('billing');
            $table->json('shipping');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
