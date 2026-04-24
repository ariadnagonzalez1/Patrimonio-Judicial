<?php

namespace App\Imports;

use App\Models\Dependencia;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class DependenciasImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 5; // Tus datos reales empiezan en la fila 5
    }

    public function model(array $row)
    {
        // Evitar filas vacías
        if (empty(array_filter($row))) {
            return null;
        }

        // Estructura esperada: [Nro, Código, Dependencia]
        $codigo = isset($row[1]) ? trim((string)$row[1]) : null;
        $nombre = isset($row[2]) ? trim((string)$row[2]) : null;

        // Saltar filas sin nombre o que son títulos
        if (empty($nombre) || str_contains(strtolower($nombre), 'dependencia')) {
            return null;
        }

        // Evitar fórmulas (=+A5+1) o códigos largos
        if ($codigo && (str_starts_with($codigo, '=') || strlen($codigo) > 10)) {
            $codigo = null;
        }

        // Generar código automático si falta
        if (!$codigo) {
            $codigo = 'DEP-' . substr(md5($nombre), 0, 6);
        }

        // Evitar duplicados
        if (Dependencia::where('codigo', $codigo)->orWhere('nombre', $nombre)->exists()) {
            Log::info("⚠️ Dependencia duplicada omitida: {$codigo} - {$nombre}");
            return null;
        }

        Log::info("✅ Dependencia agregada: {$codigo} - {$nombre}");

        return new Dependencia([
            'codigo'      => $codigo,
            'nombre'      => $nombre,
            'ubicacion'   => null,
            'responsable' => null,
            'telefono'    => null,
            'activo'      => 1,
        ]);
    }
}
