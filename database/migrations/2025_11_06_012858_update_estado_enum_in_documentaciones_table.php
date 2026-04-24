<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documentaciones', function (Blueprint $table) {
            // Cambiar el tipo de la columna 'estado'
            $table->enum('estado', ['pendiente', 'revisado', 'completo'])->default('pendiente')->change();
        });
    }

    public function down(): void
    {
        Schema::table('documentaciones', function (Blueprint $table) {
            // Volver a los valores anteriores de 'estado'
            $table->enum('estado', ['pendiente', 'revisado'])->default('pendiente')->change();
        });
    }
};

