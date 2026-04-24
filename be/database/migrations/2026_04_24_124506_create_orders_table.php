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
            $table->id('order_id');
            $table->string('queue_number')->unique();
            $table->enum('status', ['pending', 'in_progress', 'partially_ready', 'ready', 'completed', 'cancelled'])->default('pending');
            $table->enum('source', ['whatsapp', 'cashier']);
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
