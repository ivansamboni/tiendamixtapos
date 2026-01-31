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
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchase_orders')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onUpdate('cascade');
            $table->integer('cantidad');
            $table->decimal('iva', 10, 2)->nullable();
            $table->decimal('ibua', 10, 2)->nullable();
            $table->decimal('ipc', 10, 2)->nullable();
            $table->decimal('precio_unitario', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
    }
};
