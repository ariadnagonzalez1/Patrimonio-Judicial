<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE bienes MODIFY COLUMN estado 
ENUM('stock', 'asignado', 'baja', 'mantenimiento') NOT NULL DEFAULT 
'stock'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE bienes MODIFY COLUMN estado 
ENUM('stock', 'asignado', 'baja') NOT NULL DEFAULT 'stock'");
    }
};


