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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('cliente_id')->nullable()->constrained('clients')->onUpdate('cascade');
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade');
            $table->string('tipo_pago', 100)->nullable();   
            $table->decimal('impuesto', 10, 2)->nullable();        
            $table->decimal('total', 10, 2);  // Total de la orden
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
