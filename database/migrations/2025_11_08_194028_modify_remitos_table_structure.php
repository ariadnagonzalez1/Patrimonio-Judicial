<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('remitos', function (Blueprint $table) {
            // Eliminar la foreign key y columna orden_provision_id si existe
            if (Schema::hasColumn('remitos', 'orden_provision_id')) {
                $table->dropForeign(['orden_provision_id']);
                $table->dropColumn('orden_provision_id');
            }
            
            // Agregar las columnas que faltan
            $table->string('numero_expediente', 50)->nullable()->after('numero_remito');
            $table->string('orden_provision', 50)->nullable()->after('numero_expediente');
        });
    }

    public function down(): void
    {
        Schema::table('remitos', function (Blueprint $table) {
            $table->dropColumn(['numero_expediente', 'orden_provision']);
            
            // Restaurar orden_provision_id
            $table->foreignId('orden_provision_id')->constrained('ordenes_provision')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }
};
