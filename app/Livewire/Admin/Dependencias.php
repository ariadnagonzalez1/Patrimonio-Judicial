<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Dependencia;

class Dependencias extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $mostrarInactivas = false;
    public $codigo, $nombre, $ubicacion, $responsable;
    
    // Propiedades para el modal y edición
    public $showModal = false;
    public $modoEdicion = false;
    public $dependenciaId = null;

    protected $queryString = ['search', 'mostrarInactivas'];

    protected $rules = [
        'codigo' => 'required|string|max:20|unique:dependencias,codigo',
        'nombre' => 'required|string|max:255',
        'ubicacion' => 'nullable|string|max:255',
        'responsable' => 'nullable|string|max:255',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingMostrarInactivas()
    {
        $this->resetPage();
    }

    public function abrirModal()
    {
        $this->showModal = true;
        $this->modoEdicion = false;
        $this->reset(['codigo', 'nombre', 'ubicacion', 'responsable', 'dependenciaId']);
    }

    public function cerrarModal()
    {
        $this->showModal = false;
        $this->modoEdicion = false;
        $this->reset(['codigo', 'nombre', 'ubicacion', 'responsable', 'dependenciaId']);
    }

    public function guardar()
    {
        $this->validate();

        Dependencia::create([
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'ubicacion' => $this->ubicacion,
            'responsable' => $this->responsable,
            'activo' => 1,
        ]);

        $this->cerrarModal();
        session()->flash('message', 'Dependencia agregada correctamente ✅');
    }

    public function editar($id)
    {
        $dependencia = Dependencia::findOrFail($id);
        
        $this->dependenciaId = $id;
        $this->codigo = $dependencia->codigo;
        $this->nombre = $dependencia->nombre;
        $this->ubicacion = $dependencia->ubicacion;
        $this->responsable = $dependencia->responsable;
        $this->modoEdicion = true;
        $this->showModal = true;
    }

    public function actualizar()
    {
        $this->validate([
            'codigo' => 'required|string|max:20|unique:dependencias,codigo,' . $this->dependenciaId,
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'nullable|string|max:255',
            'responsable' => 'nullable|string|max:255',
        ]);

        $dependencia = Dependencia::findOrFail($this->dependenciaId);
        $dependencia->update([
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'ubicacion' => $this->ubicacion,
            'responsable' => $this->responsable,
        ]);

        $this->cerrarModal();
        session()->flash('message', 'Dependencia actualizada correctamente ✅');
    }

    public function desactivar($id)
    {
        if ($d = Dependencia::find($id)) {
            $d->update(['activo' => 0]);
            session()->flash('message', 'Dependencia desactivada 🚫');
        }
    }

    public function activar($id)
    {
        if ($d = Dependencia::find($id)) {
            $d->update(['activo' => 1]);
            session()->flash('message', 'Dependencia activada ✅');
        }
    }

    public function render()
    {
        $query = Dependencia::query();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('codigo', 'like', "%{$this->search}%")
                  ->orWhere('nombre', 'like', "%{$this->search}%")
                  ->orWhere('ubicacion', 'like', "%{$this->search}%")
                  ->orWhere('responsable', 'like', "%{$this->search}%");
            });
        }

        if (!$this->mostrarInactivas) {
            $query->where('activo', 1);
        }

        $dependencias = $query->orderBy('nombre')->paginate(10);

        return view('livewire.admin.dependencias', [
            'dependencias' => $dependencias,
        ]);
    }
}