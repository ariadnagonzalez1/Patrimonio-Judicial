<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Bien;
use App\Models\Dependencia;
use App\Models\Asignacion;

class HistoricoBienes extends Component
{
    use WithPagination;

    public $estado = 'todos';
    public $perPage = 10;
    
    // Modales
    public $modalDetalle = false;
    public $modalMantenimiento = false;
    public $modalReasignacion = false;
    public $modalBaja = false; // ✅ NUEVO
    
    public $bienSeleccionado;
    
    // Mantenimiento
    public $motivo_mantenimiento = '';
    public $fecha_resolucion;
    
    // Reasignación
    public $dependencia_destino;
    public $fecha_reasignacion;
    public $observaciones_reasignacion;
    
    // Baja ✅ NUEVO
    public $motivo_baja;
    public $fecha_baja;
    public $detalles_baja;

    protected $paginationTheme = 'tailwind';

    // Resetear página al cambiar filtros
    public function updatedEstado() { $this->resetPage(); }
    public function updatedPerPage() { $this->resetPage(); }

    public function render()
    {
        $query = Bien::with(['cuenta', 'remito', 'dependencia']);

        if ($this->estado !== 'todos') {
            $query->where('estado', $this->estado);
        }

        $bienes = $query->orderByDesc('id')->paginate($this->perPage);
        
        $dependencias = Dependencia::where('activo', 1)->orderBy('nombre')->get();

        return view('livewire.admin.historico-bienes', compact('bienes', 'dependencias'))
            ->layout('layouts.app');
    }

    public function verDetalle($id)
    {
        $this->bienSeleccionado = Bien::with([
            'cuenta',
            'remito',
            'dependencia',
            'asignaciones',
            'documentacion'
        ])->find($id);

        $this->modalDetalle = true;
    }

    public function abrirMantenimiento($id)
    {
        $this->bienSeleccionado = Bien::with([
            'remito', 
            'cuenta', 
            'dependencia', 
            'documentacion', 
            'asignaciones'
        ])->findOrFail($id);
        
        $this->motivo_mantenimiento = '';
        $this->fecha_resolucion = null;
        $this->modalMantenimiento = true;
    }

    public function guardarMantenimiento()
    {
        $this->validate([
            'motivo_mantenimiento' => 'required|string|min:5',
            'fecha_resolucion'     => 'nullable|date',
        ]);

        $bien = Bien::findOrFail($this->bienSeleccionado->id);
        $bien->estado = 'mantenimiento';
        $bien->save();

        $this->modalMantenimiento = false;
        $this->resetPage();

        session()->flash('message', 'El bien fue marcado para mantenimiento.');
    }

    public function abrirReasignacion($id)
    {
        $this->bienSeleccionado = Bien::with('dependencia')->findOrFail($id);
        
        $this->dependencia_destino = null;
        $this->fecha_reasignacion = now()->format('Y-m-d');
        $this->observaciones_reasignacion = null;
        
        $this->modalReasignacion = true;
    }

    public function guardarReasignacion()
    {
        $this->validate([
            'dependencia_destino' => 'required|exists:dependencias,id',
            'fecha_reasignacion' => 'required|date',
            'observaciones_reasignacion' => 'nullable|string|max:500',
        ], [
            'dependencia_destino.required' => 'Debe seleccionar una dependencia destino.',
            'dependencia_destino.exists' => 'La dependencia seleccionada no existe.',
            'fecha_reasignacion.required' => 'La fecha de reasignación es obligatoria.',
        ]);

        if ($this->bienSeleccionado->dependencia_id == $this->dependencia_destino) {
            session()->flash('error', 'El bien ya está asignado a esa dependencia.');
            return;
        }

        Asignacion::create([
            'bien_id' => $this->bienSeleccionado->id,
            'dependencia_id' => $this->dependencia_destino,
            'fecha_asignacion' => $this->fecha_reasignacion,
            'user_id' => auth()->id(),
            'observacion' => $this->observaciones_reasignacion,
        ]);

        $bien = Bien::findOrFail($this->bienSeleccionado->id);
        $bien->dependencia_id = $this->dependencia_destino;
        $bien->estado = 'asignado';
        $bien->save();

        $this->modalReasignacion = false;
        $this->resetPage();

        session()->flash('message', 'Bien reasignado correctamente.');
    }

    // ✅ NUEVO: Abrir modal de baja
    public function abrirBaja($id)
    {
        $this->bienSeleccionado = Bien::with('dependencia')->findOrFail($id);
        
        // Limpiar formulario
        $this->motivo_baja = null;
        $this->fecha_baja = now()->format('Y-m-d');
        $this->detalles_baja = null;
        
        $this->modalBaja = true;
    }

    // ✅ NUEVO: Confirmar baja
    public function confirmarBaja()
    {
        $this->validate([
            'motivo_baja' => 'required|string',
            'fecha_baja' => 'required|date',
            'detalles_baja' => 'required|string|min:10',
        ], [
            'motivo_baja.required' => 'Debe seleccionar un motivo de baja.',
            'fecha_baja.required' => 'La fecha de baja es obligatoria.',
            'detalles_baja.required' => 'Los detalles son obligatorios.',
            'detalles_baja.min' => 'Los detalles deben tener al menos 10 caracteres.',
        ]);

        // Actualizar el bien
        $bien = Bien::findOrFail($this->bienSeleccionado->id);
        $bien->estado = 'baja';
        $bien->fecha_baja = $this->fecha_baja;
        $bien->causa_baja = "{$this->motivo_baja}: {$this->detalles_baja}";
        $bien->save();

        // Cerrar modal
        $this->modalBaja = false;

        // Resetear página
        $this->resetPage();

        session()->flash('message', 'El bien ha sido dado de baja correctamente.');
    }
}