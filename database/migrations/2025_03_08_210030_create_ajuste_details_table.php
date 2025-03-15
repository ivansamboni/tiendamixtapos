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
        Schema::create('ajuste_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ajuste_id')->constrained('ajustes')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onUpdate('cascade');
            $table->integer('stock_cambio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajuste_details');
    }
};
