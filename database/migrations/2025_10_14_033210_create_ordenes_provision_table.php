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
        Schema::create('ordenes_provision', function (Blueprint $table) {
            $table->id();
            $table->string('numero_orden', 50)->unique();          // número de orden de pago
            $table->date('fecha_orden');
            $table->string('numero_expediente', 50);               // expediente vinculado
            $table->string('orden_provision', 50)->nullable();     // 👈 nuevo campo agregado
            $table->foreignId('proveedor_id')
                  ->constrained('proveedores')
                  ->onUpdate('cascade');
            $table->decimal('monto_autorizado', 15, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->index('numero_expediente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenes_provision');
    }
};

