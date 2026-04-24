<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bienes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lote_padre_id')->nullable();
            $table->boolean('es_lote')->default(false);
            $table->string('numero_inventario', 50)->unique();
            $table->text('descripcion');
            $table->integer('cantidad')->default(1);
            $table->decimal('precio_unitario', 15, 2);
            $table->decimal('monto_total', 15, 2)->nullable();
            $table->boolean('bien_uso')->default(false);
            $table->boolean('bien_consumo')->default(false);
            $table->enum('estado', ['stock', 'asignado', 'baja'])->default('stock');
            $table->date('fecha_baja')->nullable();
            $table->text('causa_baja')->nullable();
            $table->unsignedBigInteger('cuenta_id');
            $table->unsignedBigInteger('remito_id');
            $table->unsignedBigInteger('dependencia_id')->nullable();
            $table->unsignedBigInteger('proveedor_id');
            $table->string('codigo_qr', 255)->nullable();
            $table->timestamps();
            
            // Índices
            $table->index('estado');
            $table->index('numero_inventario');
            $table->index('lote_padre_id');
            $table->index('es_lote');
        });

       
        Schema::table('bienes', function (Blueprint $table) {
            $table->foreign('lote_padre_id')
                ->references('id')
                ->on('bienes')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                
            $table->foreign('cuenta_id')
                ->references('id')
                ->on('cuentas')
                ->onUpdate('cascade');
                
            $table->foreign('remito_id')
                ->references('id')
                ->on('remitos')
                ->onUpdate('cascade');
                
            $table->foreign('proveedor_id')
                ->references('id')
                ->on('proveedores')
                ->onUpdate('cascade');
                
          
            $table->foreign('dependencia_id')
                ->references('id')
                ->on('dependencias')
                ->nullOnDelete()  // ← Esto genera SET NULL correctamente
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bienes');
    }
};