<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bien_id');
            $table->string('numero_acta', 50)->nullable();
            $table->date('fecha_acta')->nullable();
            $table->string('numero_resolucion', 50)->nullable();
            $table->string('numero_factura', 50)->nullable();
            $table->date('fecha_factura')->nullable();
            $table->decimal('monto', 15, 2)->nullable();
            $table->string('partida_presupuestaria', 100)->nullable();
            $table->string('orden_pago', 50)->nullable();
            $table->year('ejercicio')->nullable();
            $table->unsignedBigInteger('orden_provision_id')->nullable();
            $table->enum('estado', ['pendiente', 'completo', 'revisado'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            $table->unique('bien_id');
            $table->index('numero_factura');
            $table->index('orden_pago');
        });

        Schema::table('documentaciones', function (Blueprint $table) {
            $table->foreign('bien_id')
                ->references('id')
                ->on('bienes')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                
            $table->foreign('orden_provision_id')
                ->references('id')
                ->on('ordenes_provision')
                ->nullOnDelete()
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentaciones');
    }
};