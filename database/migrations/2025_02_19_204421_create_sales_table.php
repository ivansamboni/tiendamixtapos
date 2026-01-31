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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caja_id')->nullable()->constrained();
            $table->foreignId('cliente_id')->nullable()->constrained('clients');
            $table->foreignId('user_id')->constrained('users');
            $table->uuid('uuid')->unique();       
            // --- CAMPOS PARA FACTUS / DIAN ---
            $table->string('bill_id_factus')->nullable(); // ID único que le da Factus a la factura
            $table->integer('numbering_range_id')->nullable(); // ID de la resolución usada (Prefijo)
            $table->string('factura_numero', 100)->nullable(); // Ej: SETT-123 (Consecutivo legal)

            // El UUID que ya tienes úsalo para guardar el CUFE (Código Único de Factura Electrónica)
            $table->string('cufe', 255)->nullable()->unique();

            // URLs de los documentos legales
            $table->string('qr_url')->nullable();  // URL de la imagen del QR
            $table->string('xml_url')->nullable(); // URL del XML legal
            $table->string('pdf_url')->nullable(); // URL del PDF (Representación gráfica)

            // --- DATOS COMERCIALES ---
            $table->string('forma_pago', 2)->nullable();      // 1=contado | 2=crédito
            $table->string('metodo_pago', 5)->nullable();     // Códigos DIAN: 10 (Efectivo), 42 (Transf), etc.
            $table->decimal('impuesto', 15, 2)->default(0);
            $table->decimal('total', 15, 2);

            // Estado de la factura ante la DIAN
            $table->enum('status_dian', ['proceso', 'aceptada', 'rechazada', 'error'])
                ->default('proceso');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
