<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bien_id')->constrained('bienes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('dependencia_id')->constrained('dependencias')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate();
            $table->foreignId('recibido_por')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->date('fecha_asignacion');
            $table->text('observacion')->nullable();
            $table->timestamps();

            $table->index(['bien_id', 'fecha_asignacion']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignaciones');
    }
};
