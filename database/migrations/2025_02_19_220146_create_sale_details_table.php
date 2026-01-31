<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->foreignId('caja_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('producto_id')->constrained('productos')->onUpdate('cascade');
            $table->decimal('cantidad', 10, 3);
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
        Schema::dropIfExists('sale_details');
    }
};
