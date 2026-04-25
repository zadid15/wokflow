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
        Schema::create('order_item_attributes', function (Blueprint $table) {
            $table->id('order_item_attribute_id');
            $table->foreignId('order_item_id')->constrained('order_items', 'order_item_id');
            $table->foreignId('attribute_id')->constrained('attributes', 'attribute_id');
            $table->boolean('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item_attributes');
    }
};
