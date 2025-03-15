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
    Schema::create('productos', function (Blueprint $table) {
        $table->id();
        $table->string('codigo_barras')->nullable()->unique();
        $table->text('nombre')->unique();
        $table->text('descripcion')->nullable();
        $table->foreignId('marca_id')->nullable()->constrained('marcas')->onUpdate('cascade');
        $table->foreignId('categoria_id')->nullable()->constrained('categories')->onUpdate('cascade');        
        $table->decimal('precio_venta', 10, 2)->nullable();
        $table->decimal('precio_compra', 10, 2)->nullable();
        $table->decimal('iva', 10, 2)->nullable();
        $table->decimal('ibua', 10, 2)->nullable();
        $table->decimal('ganancia', 10, 2)->nullable();
        $table->integer('stock')->default(0);
        $table->integer('stock_minimo')->default(0);
        $table->foreignId('proveedor_id')->nullable()->constrained('sellers')->onUpdate('cascade');
        $table->string('img1')->nullable();        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
