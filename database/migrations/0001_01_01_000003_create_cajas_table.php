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
        if (!Schema::hasTable('cajas')) {
            Schema::create('cajas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained();
                $table->text('caja_numero')->nullable();
                $table->timestamp('apertura')->useCurrent();
                $table->decimal('monto_inicial', 10, 2);
                $table->timestamp('cierre')->nullable();
                $table->decimal('monto_final', 10, 2)->nullable();
                $table->text('observaciones')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cajas');
    }
};
