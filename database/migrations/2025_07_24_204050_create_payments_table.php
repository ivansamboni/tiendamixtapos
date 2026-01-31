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

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caja_id')->nullable()->constrained();
            $table->foreignId('credit_id')->constrained('credits')->onDelete('cascade');
            $table->decimal('monto', 10, 2);
            $table->string('metodo_pago', 5)->nullable();
            $table->date('fecha_abono');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
