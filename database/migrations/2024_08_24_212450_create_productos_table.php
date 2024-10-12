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
        $table->foreignId('categoria_id')->nullable()->constrained('categorias')->onUpdate('cascade');        
        $table->decimal('precio_venta');
        $table->decimal('precio_compra');
        $table->integer('stock'); 
        $table->foreignId('proveedor_id')->nullable()->constrained('proveedors')->onUpdate('cascade');
        $table->string('img1')->nullable();
        $table->string('img2')->nullable();
        $table->string('img3')->nullable();
        $table->string('img4')->nullable();
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
