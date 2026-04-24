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
        // 💣 Primero eliminamos la clave foránea si existe
        $table->dropForeign(['orden_provision_id']);
        // 💥 Luego la columna
        $table->dropColumn('orden_provision_id');
    });
}

public function down(): void
{
    Schema::table('documentaciones', function (Blueprint $table) {
        // 🔁 Para revertir en caso de rollback
        $table->unsignedBigInteger('orden_provision_id')->nullable();
        $table->foreign('orden_provision_id')->references('id')->on('ordenes_provision');
    });
}

};
