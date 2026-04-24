<?php

namespace App\Livewire;

use App\Models\Bien;
use App\Models\Documentacion;
use App\Models\Proveedor;
use App\Models\OrdenProvision;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BienesDocumentacionExport;

class DataentryPanel extends Component
{
    public $busqueda = '';
    public $bienSeleccionado = null;

    // Campos de documentación
    public $numero_acta = '';
    public $fecha_acta = '';
    public $numero_resolucion = '';
    public $numero_factura = '';
    public $fecha_factura = '';
    public $monto = '';
    public $proveedor_id = '';
    public $partida_presupuestaria = '';
    public $orden_pago = '';
    public $ejercicio = '';
    public $orden_provision_id = '';
    public $estado = 'pendiente';
    public $observaciones = '';

    // Listas y modales
    public $bienesCompletos = [];
    public $modalDetalles = false;
    public $bienDetalle = null;
    public $modalSinAsignar = false;

    // Fechas para exportar
    public $showExportModal = false;
    public $fechaInicio;
    public $fechaFin;
    public $grupoSeleccionado = null;
    public $bienesDelGrupo = [];

    protected $listeners = ['refrescarPendientes' => '$refresh'];

    public function mount()
    {
        $this->fecha_acta = date('Y-m-d');
        $this->fecha_factura = date('Y-m-d');
        $this->ejercicio = date('Y');
    }

    public function buscar()
{
    $this->validate(
        ['busqueda' => 'required|string'],
        ['busqueda.required' => 'Debe ingresar un número de remito u orden de provisión']
    );

    // Buscar bienes por REMITO o por ORDEN DE PROVISIÓN
    $bienes = Bien::whereHas('remito', function ($q) {
            $q->where('numero_remito', $this->busqueda)
              ->orWhere('orden_provision', $this->busqueda);
        })
        ->with(['cuenta', 'remito'])
        ->get();

    if ($bienes->isEmpty()) {
        session()->flash(
            'error',
            '❌ No se encontraron bienes para ese remito u orden de provisión'
        );

        $this->grupoSeleccionado = null;
        $this->bienesDelGrupo = [];
        $this->bienSeleccionado = null;
        return;
    }

    // Construir la key del grupo IGUAL a getPendientesProperty
    $key = ($bienes->first()->remito->numero_expediente ?? 'SIN_EXP')
         . '|'
         . ($bienes->first()->remito->orden_provision ?? 'SIN_OP');

    $this->grupoSeleccionado = $key;
    $this->bienesDelGrupo = $bienes->pluck('id')->toArray();
    $this->bienSeleccionado = null;

    // Cargar documentación del primer bien
    $this->cargarDocumentacion($bienes->first()->id);

session()->flash('message', '✅ Encontrados ' . $bienes->count() . ' bienes');

$this->dispatch('scroll-a-formulario');
return;
}

    

    /** Ver detalles */
    public function verDetalles($bienId)
    {
        $this->bienDetalle = Bien::with(['remito', 'proveedor', 'documentacion'])->find($bienId);
        $this->modalDetalles = true;
    }

    /** Cargar documentación */
    public function cargarDocumentacion($bienId)
    {
        $bien = Bien::with('documentacion')->find($bienId);

        if ($bien && $bien->documentacion) {
            $doc = $bien->documentacion;
            $this->numero_acta = $doc->numero_acta ?? '';
            $this->fecha_acta = $doc->fecha_acta ? Carbon::parse($doc->fecha_acta)->format('Y-m-d') : date('Y-m-d');
            $this->numero_resolucion = $doc->numero_resolucion ?? '';
            $this->numero_factura = $doc->numero_factura ?? '';
            $this->fecha_factura = $doc->fecha_factura ? Carbon::parse($doc->fecha_factura)->format('Y-m-d') : date('Y-m-d');
            $this->monto = $doc->monto ?? '';
            $this->proveedor_id = $doc->proveedor_id ?? '';
            $this->partida_presupuestaria = $doc->partida_presupuestaria ?? '';
            $this->orden_pago = $doc->orden_pago ?? '';
            $this->ejercicio = $doc->ejercicio ?? date('Y');
            $this->orden_provision_id = $doc->orden_provision_id ?? '';
            $this->estado = $doc->estado ?? 'pendiente';
            $this->observaciones = $doc->observaciones ?? '';
        } else {
            $this->limpiarCampos();
        }
    }

    private function validarCamposUnicos(array $exceptBienIds = []): bool
{
    // Limpia errores previos SOLO de estos campos (opcional)
    foreach (['numero_acta','numero_factura','numero_resolucion','partida_presupuestaria','orden_pago'] as $campo) {
        $this->resetValidation($campo);
    }

    $campos = [
        'numero_acta' => $this->numero_acta,
        'numero_factura' => $this->numero_factura,
        'numero_resolucion' => $this->numero_resolucion,
        'partida_presupuestaria' => $this->partida_presupuestaria,
        'orden_pago' => $this->orden_pago,
    ];

    $hayErrores = false;

    foreach ($campos as $campo => $valor) {
        $valor = is_string($valor) ? trim($valor) : $valor;

        // si está vacío, no validamos duplicado
        if ($valor === null || $valor === '') {
            continue;
        }

        $existe = Documentacion::query()
            ->where($campo, $valor)
            ->when(!empty($exceptBienIds), function ($q) use ($exceptBienIds) {
                $q->whereNotIn('bien_id', $exceptBienIds);
            })
            ->exists();

        if ($existe) {
            $hayErrores = true;

            // Mensaje específico por campo (más claro)
            $mensajes = [
                'numero_acta' => 'El Número de Acta ya está registrado en otro bien.',
                'numero_factura' => 'El Número de Factura ya está registrado en otro bien.',
                'numero_resolucion' => 'El Número de Resolución ya está registrado en otro bien.',
                'partida_presupuestaria' => 'La Partida Presupuestaria ya está registrada en otro bien.',
                'orden_pago' => 'La Orden de Pago ya está registrada en otro bien.',
            ];

            $this->addError($campo, $mensajes[$campo] ?? 'Este valor ya existe en otro registro.');
        }
    }

    return !$hayErrores; // true = OK, false = hay duplicados
}



    /** Guardar documentación */
    public function guardarDocumentacion()
    {
        $bien = Bien::find($this->bienSeleccionado);

        if (!$bien) {
            session()->flash('error', '❌ Bien no encontrado.');
            return;
        }

        if (!$this->validarCamposUnicos([$bien->id])) {
    session()->flash('error', '❌ Hay campos duplicados. Revisá los mensajes en rojo.');
    return;
}

        Documentacion::updateOrCreate(
            ['bien_id' => $bien->id],
            [
                'numero_acta' => $this->numero_acta,
                'fecha_acta' => $this->fecha_acta,
                'numero_resolucion' => $this->numero_resolucion,
                'numero_factura' => $this->numero_factura,
                'fecha_factura' => $this->fecha_factura,
                'monto' => $this->monto,
                'proveedor_id' => $this->proveedor_id,
                'partida_presupuestaria' => $this->partida_presupuestaria,
                'orden_pago' => $this->orden_pago,
                'ejercicio' => $this->ejercicio,
                'orden_provision_id' => $this->orden_provision_id,
                'estado' => $this->estado,
                'observaciones' => $this->observaciones,
            ]
        );

        session()->flash('message', '✅ Documentación guardada correctamente');
    }

    public function limpiarCampos()
    {
        $this->numero_acta = '';
        $this->fecha_acta = date('Y-m-d');
        $this->numero_resolucion = '';
        $this->numero_factura = '';
        $this->fecha_factura = date('Y-m-d');
        $this->monto = '';
        $this->proveedor_id = '';
        $this->partida_presupuestaria = '';
        $this->orden_pago = '';
        $this->ejercicio = date('Y');
        $this->orden_provision_id = '';
        $this->estado = 'pendiente';
        $this->observaciones = '';
    }

    /** Mostrar / cerrar modales */
    public function mostrarModal($tipo)
    {
        if ($tipo === 'sin-asignar') {
            $this->obtenerBienesCompletos();
            $this->modalSinAsignar = true;
        }

        if ($tipo === 'exportar') {
            $this->showExportModal = true;
        }
    }

    public function cerrarModal($tipo)
    {
        if ($tipo === 'sin-asignar') $this->modalSinAsignar = false;
        if ($tipo === 'exportar') $this->showExportModal = false;
        if ($tipo === 'detalles') $this->modalDetalles = false;
    }

    /** Bienes pendientes */
    public function getPendientesProperty()
{
    $bienes = Bien::whereDoesntHave('documentacion')
        ->orWhereHas('documentacion', function ($q) {
            $q->where('estado', '!=', 'completo');
        })
        ->with(['cuenta', 'remito'])
        ->latest()
        ->take(20)
        ->get();

    // Agrupar por expediente y orden de provisión
    return $bienes->groupBy(function($bien) {
        if ($bien->remito && $bien->remito->numero_expediente && $bien->remito->orden_provision) {
            return $bien->remito->numero_expediente . '|' . $bien->remito->orden_provision;
        }
        return 'individual_' . $bien->id;
    })->map(function($grupo) {
        return [
            'items' => $grupo,
            'numero_expediente' => $grupo->first()->remito->numero_expediente ?? 'N/A',
            'orden_provision' => $grupo->first()->remito->orden_provision ?? 'N/A',
            'numero_remito' => $grupo->first()->remito->numero_remito ?? 'N/A',
            'cantidad' => $grupo->count(),
        ];
    });
}

    public function getBienesSinAsignarProperty()
{
    $bienes = Bien::whereHas('documentacion', function ($q) {
        $q->where('estado', 'completo');
    })
    ->with(['cuenta', 'remito', 'documentacion'])
    ->latest()
    ->get();

    // Agrupar por expediente y orden de provisión
    return $bienes->groupBy(function($bien) {
        if ($bien->remito && $bien->remito->numero_expediente && $bien->remito->orden_provision) {
            return $bien->remito->numero_expediente . '|' . $bien->remito->orden_provision;
        }
        return 'individual_' . $bien->id;
    })->map(function($grupo) {
        return [
            'items' => $grupo,
            'numero_expediente' => $grupo->first()->remito->numero_expediente ?? 'N/A',
            'orden_provision' => $grupo->first()->remito->orden_provision ?? 'N/A',
            'numero_remito' => $grupo->first()->remito->numero_remito ?? 'N/A',
            'cantidad' => $grupo->count(),
        ];
    });
}

    public function obtenerBienesCompletos()
    {
        $this->bienesCompletos = Bien::whereHas('documentacion', function ($query) {
            $query->where('estado', 'completo');
        })
        ->with(['documentacion'])
        ->get();
    }

    /** Exportar a Excel */
    public function exportarExcelPorFechas()
    {
        $this->validate([
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date|after_or_equal:fechaInicio',
        ]);

        $this->showExportModal = false;

        return Excel::download(
            new BienesDocumentacionExport($this->fechaInicio, $this->fechaFin),
            'bienes_documentacion_' . now()->format('Ymd_His') . '.xlsx'
        );
    }


    
public function exportarTodo()
{
    $this->showExportModal = false;

    return \Maatwebsite\Excel\Facades\Excel::download(
        new \App\Exports\BienesDocumentacionExport(),
        'bienes_documentacion_todos_' . now()->format('Ymd_His') . '.xlsx'
    );
}


    public function seleccionarBien($bienId)
{
    $this->grupoSeleccionado = null; // limpiar grupo activo
    $this->bienesDelGrupo = []; // limpiar posibles grupos previos
    $this->bienSeleccionado = $bienId;
    $this->cargarDocumentacion($bienId);
}


// Nuevo método para seleccionar grupo completo
public function seleccionarGrupo($key)
{
    $this->grupoSeleccionado = $key;
    
    // Obtener todos los bienes del grupo
    $grupo = $this->pendientes->get($key);
    
    if ($grupo) {
        $this->bienesDelGrupo = $grupo['items']->pluck('id')->toArray();
        
        // Cargar la documentación del primer bien (si existe)
        // porque todos tendrán la misma
        $primerBien = $grupo['items']->first();
        if ($primerBien) {
            $this->cargarDocumentacion($primerBien->id);
        }
    }
}

// Modificar el método guardarDocumentacion para aplicar a todos los bienes del grupo
public function guardarDocumentacionGrupo()
{
    if (empty($this->bienesDelGrupo)) {
        session()->flash('error', '❌ No hay bienes seleccionados en el grupo.');
        return;
    }

    $documentacionData = [
        'numero_acta' => $this->numero_acta,
        'fecha_acta' => $this->fecha_acta,
        'numero_resolucion' => $this->numero_resolucion,
        'numero_factura' => $this->numero_factura,
        'fecha_factura' => $this->fecha_factura,
        'monto' => $this->monto,
        'proveedor_id' => $this->proveedor_id,
        'partida_presupuestaria' => $this->partida_presupuestaria,
        'orden_pago' => $this->orden_pago,
        'ejercicio' => $this->ejercicio,
        'orden_provision_id' => $this->orden_provision_id,
        'estado' => $this->estado,
        'observaciones' => $this->observaciones,
    ];

    if (!$this->validarCamposUnicos()) {
    session()->flash('error', '❌ Hay campos duplicados. Revisá los mensajes en rojo.');
    return;
}

    // Aplicar la misma documentación a todos los bienes del grupo
    foreach ($this->bienesDelGrupo as $bienId) {
        Documentacion::updateOrCreate(
            ['bien_id' => $bienId],
            $documentacionData
        );
    }

    session()->flash('message', '✅ Documentación guardada para ' . count($this->bienesDelGrupo) . ' bienes');
    
    // Limpiar selección
    $this->grupoSeleccionado = null;
    $this->bienesDelGrupo = [];
    $this->limpiarCampos();

}

    public function render()
    {
        $this->obtenerBienesCompletos();

        return view('livewire.dataentry-panel', [
            'bienesCompletos' => $this->bienesCompletos,
            'proveedores' => Proveedor::where('estado', 1)->orderBy('razon_social')->get(),
            'ordenesProvision' => OrdenProvision::orderBy('id')->get(),
        ])->layout('components.admin-layout', [
            'title' => 'Panel de Cargador',
        ]);
    }
}
