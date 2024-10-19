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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('nombres', 100);
            $table->string('apellidos', 100)->nullable();
            $table->string('cedula', 100);
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->string('departamento', 100);
            $table->string('ciudad', 100);
            $table->string('direccion', 100);
            $table->string('comprobante_pago', 100);
            $table->decimal('total', 10);  // Total de la orden
            $table->string('estado', 20)->default('pendiente');  // Ej: pendiente, pagado, cancelado
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
