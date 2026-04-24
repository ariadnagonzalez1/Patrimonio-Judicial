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
    Schema::table('documentaciones', function (Blueprint $table) {
        // Solo eliminar si existe
        if (Schema::hasColumn('documentaciones', 'proveedor_id')) {
            try {
                $table->dropForeign(['proveedor_id']);
            } catch (\Exception $e) {
                // ignora si ya no existe
            }

            $table->dropColumn('proveedor_id');
        }
    });
}


public function down()
{
    Schema::table('documentaciones', function (Blueprint $table) {
        // Revertir la eliminación de la columna
        $table->unsignedBigInteger('proveedor_id')->nullable();
        // Revertir la clave foránea
        $table->foreign('proveedor_id')->references('id')->on('proveedores')->onDelete('set null');
    });
}

};
