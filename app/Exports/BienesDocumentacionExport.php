<?php

namespace App\Exports;

use App\Models\Documentacion;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class BienesDocumentacionExport implements FromView
{
    use Exportable;

    protected $fechaInicio;
    protected $fechaFin;

    /**
     * Constructor opcional (puede ser nulo para exportar todo)
     */
    public function __construct($fechaInicio = null, $fechaFin = null)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    /**
     * Devuelve la vista con los datos a exportar
     */
    public function view(): View
    {
        $documentaciones = Documentacion::with([
                'bien.remito',
                'bien.proveedor',
                'bien.cuenta',
                'bien.dependencia'
            ])
            ->when($this->fechaInicio && $this->fechaFin, function ($query) {
                // Si se pasa un rango, filtramos
                $query->whereBetween('fecha_acta', [$this->fechaInicio, $this->fechaFin]);
            })
            ->orderBy('fecha_acta', 'asc')
            ->get();

        return view('exports.bienes-documentacion', [
            'documentaciones' => $documentaciones,
            'rango' => [
                'inicio' => $this->fechaInicio,
                'fin' => $this->fechaFin
            ],
        ]);
    }
}
