<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Proveedor;

class Proveedores extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $mostrarInactivos = false;

    public $showModal = false;
    public $modoEdicion = false;

    public $razon_social, $cuil, $email, $proveedor_id;

    protected $rules = [
        'razon_social' => 'required|string|max:255',
        'cuil' => 'required|string|max:20',
        'email' => 'nullable|email',
    ];

    // 🔍 Resetea la paginación al buscar o cambiar el filtro
    public function updatingSearch() { $this->resetPage(); }
    public function updatingMostrarInactivos() { $this->resetPage(); }

    // 🔄 Render principal
    public function render()
    {
        $query = Proveedor::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('razon_social', 'like', "%{$this->search}%")
                  ->orWhere('cuil', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        if (!$this->mostrarInactivos) {
            $query->where('estado', 1);
        }

        $proveedores = $query->orderBy('razon_social')->paginate(10);

        return view('livewire.admin.proveedores', [
            'proveedores' => $proveedores,
        ]);
    }

    // 🟦 Abrir modal para crear nuevo proveedor
    public function abrirModal()
    {
        $this->reset(['razon_social', 'cuil', 'email', 'proveedor_id']);
        $this->modoEdicion = false;
        $this->showModal = true;
    }

    // 🔴 Cerrar modal
    public function cerrarModal()
    {
        $this->showModal = false;
    }

    // ✅ Guardar un nuevo proveedor
    public function guardarProveedor()
    {
        $this->validate();

        Proveedor::create([
            'razon_social' => $this->razon_social,
            'cuil' => $this->cuil,
            'email' => $this->email,
            'estado' => 1,
        ]);

        $this->reset(['razon_social', 'cuil', 'email', 'showModal']);
        session()->flash('message', 'Proveedor agregado correctamente ✅');
    }

    // ✏️ Cargar datos en el modal para editar
    public function editar($id)
    {
        $p = Proveedor::find($id);

        if ($p) {
            $this->proveedor_id = $p->id;
            $this->razon_social = $p->razon_social;
            $this->cuil = $p->cuil;
            $this->email = $p->email;

            $this->modoEdicion = true;
            $this->showModal = true;
        }
    }

    // 💾 Actualizar proveedor existente
    public function actualizarProveedor()
    {
        $this->validate();

        if ($this->proveedor_id) {
            $p = Proveedor::find($this->proveedor_id);
            if ($p) {
                $p->update([
                    'razon_social' => $this->razon_social,
                    'cuil' => $this->cuil,
                    'email' => $this->email,
                ]);

                $this->reset(['showModal', 'modoEdicion', 'razon_social', 'cuil', 'email', 'proveedor_id']);
                session()->flash('message', 'Proveedor actualizado correctamente ✏️');
            }
        }
    }

    // 🚫 Desactivar proveedor
    public function darDeBajaProveedor($id)
    {
        if ($p = Proveedor::find($id)) {
            $p->update(['estado' => 0]);
            session()->flash('message', 'Proveedor dado de baja 🚫');
        }
    }

    // ✅ Reactivar proveedor
    public function activarProveedor($id)
    {
        if ($p = Proveedor::find($id)) {
            $p->update(['estado' => 1]);
            session()->flash('message', 'Proveedor reactivado ✅');
        }
    }
}
