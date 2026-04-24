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
        Schema::create('remitos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_remito', 50)->unique();
            $table->string('numero_expediente', 50)->nullable(); // ✅ nuevo campo
            $table->string('orden_provision', 50)->nullable();    // ✅ nuevo campo
            $table->date('fecha_recepcion');
            $table->string('foto_remito', 255)->nullable();
            $table->enum('tipo_compra', ['directa', 'licitacion'])->default('directa');
            $table->foreignId('proveedor_id')->constrained('proveedores')->onUpdate('cascade');
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade');
            $table->timestamps();
            $table->index('numero_remito');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remitos');
    }
};


