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
        Schema::create('notas_compras', function (Blueprint $table) {
            $table->id();
            $table->string('referencia_proveedor')->nullable();
            $table->date('fecha_emision');
            $table->string('numero_nota')->unique(); // NC-000123
            $table->enum('tipo', ['credito', 'debito']);
            $table->foreignId('purchase_id')->constrained('purchase_orders')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained();
            $table->decimal('total', 10, 2);
            $table->text('motivo');
            $table->string('payment_method_code')->default('10');
            // --- Campos Técnicos FACTUS / DIAN ---
            $table->integer('numbering_range_id')->nullable(); // ID del rango de numeración en Factus
            $table->integer('correction_concept_code')->nullable(); // El motivo (1, 2, 3, 4...)
            $table->string('bill_id_factus')->nullable(); // El ID de la factura en la plataforma Factus
            $table->string('uuid_factus')->nullable(); // El CUDE devuelto por la DIAN
            $table->string('xml_url')->nullable(); // URL del archivo XML legal
            $table->string('pdf_url')->nullable(); // URL del PDF legal     
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas_compras');
    }
};
