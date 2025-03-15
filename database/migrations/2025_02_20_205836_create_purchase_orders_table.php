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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('factura_numero', 100)->unique();
            $table->string('user_id', 100)->nullable();
            $table->foreignId('seller_id')->nullable()->constrained('sellers')->onUpdate('cascade'); // Proveedor
            $table->date('order_date');
            $table->string('status')->default('pendiente');
            $table->string('tipo_pago', 100)->nullable();
            $table->decimal('total', 10, 2);  // Total de la orden
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
