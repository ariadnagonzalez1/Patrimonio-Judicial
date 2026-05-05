<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bienes', function (Blueprint $table) {
            if (!Schema::hasColumn('bienes', 'foto_remito')) {
                $table->string('foto_remito')->nullable()->after('foto');
            }
            if (!Schema::hasColumn('bienes', 'empleado_recibe')) {
                $table->string('empleado_recibe')->nullable()->after('foto_remito');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bienes', function (Blueprint $table) {
            if (Schema::hasColumn('bienes', 'foto_remito')) {
                $table->dropColumn('foto_remito');
            }
            if (Schema::hasColumn('bienes', 'empleado_recibe')) {
                $table->dropColumn('empleado_recibe');
            }
        });
    }
};