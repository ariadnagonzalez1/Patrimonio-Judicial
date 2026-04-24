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
        Schema::create('proveedores', function (Blueprint $table) {
    $table->id();
    $table->string('razon_social', 255);
    $table->string('cuil', 20);
    $table->string('nombre_contacto', 100)->nullable();
    $table->string('telefono', 30)->nullable();
    $table->string('email', 120)->nullable();
    $table->string('direccion', 255)->nullable();
    $table->boolean('estado')->default(true);
    $table->timestamps();
    $table->index('cuil');
    $table->index('razon_social');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
