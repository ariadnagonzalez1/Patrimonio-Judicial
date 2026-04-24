<?php

namespace App\Imports;

use App\Models\Cuenta;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CuentasImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2; // La fila 1 tiene los encabezados "Numero" y "Detalle"
    }

    public function model(array $row)
    {
        // Evitar filas vacías
        if (empty($row[0]) && empty($row[1])) {
            return null;
        }

        $codigo = trim((string)$row[0]); // "Número"
        $descripcion = isset($row[1]) ? trim((string)$row[1]) : null; // "Detalle"

        // Si no hay descripción, la dejamos vacía pero sin interrumpir
        if (!$descripcion) {
            $descripcion = 'Sin descripción';
        }

        // Evitar duplicados por código
        if (Cuenta::where('codigo', $codigo)->exists()) {
            Log::info("⚠️ Cuenta duplicada omitida: {$codigo} - {$descripcion}");
            return null;
        }

        Log::info("✅ Cuenta agregada: {$codigo} - {$descripcion}");

        return new Cuenta([
            'codigo'      => $codigo,
            'descripcion' => $descripcion,
            'tipo'        => 'uso',     // valor por defecto
            'activo'      => true,      // valor por defecto
        ]);
    }
}
