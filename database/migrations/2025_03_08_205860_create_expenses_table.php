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
        Schema::create('expenses', function (Blueprint $table) {           
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onUpdate('cascade');
                $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders')->onDelete('cascade');
                $table->string('tipo_gasto', 100); // Ej: Compra mercadería, Luz, Agua, Nómina, Transporte, etc.
                $table->decimal('monto', 10, 2);
                $table->date('fecha');
                $table->text('descripcion')->nullable();
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
