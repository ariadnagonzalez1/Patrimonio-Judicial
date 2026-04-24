<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Bien;
use App\Models\Dependencia;
use App\Models\Proveedor;
use App\Models\Cuenta;
use App\Models\Remito;

class Bienes extends Component
{
    use WithPagination;

    public $search = '';
    public $mostrarBajas = false;
    public $showModal = false;

    // Campos del formulario
    public $numero_inventario, $descripcion, $cantidad = 1, $precio_unitario,
           $bien_uso = true, $bien_consumo = false, $estado = 'stock',
           $dependencia_id, $proveedor_id, $cuenta_id, $remito_id;

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'numero_inventario' => 'required|string|max:50|unique:bienes,numero_inventario',
        'descripcion' => 'required|string|max:255',
        'cantidad' => 'required|integer|min:1',
        'precio_unitario' => 'required|numeric|min:0',
        'estado' => 'required|string',
        'dependencia_id' => 'nullable|exists:dependencias,id',
        'proveedor_id' => 'required|exists:proveedores,id',
        'cuenta_id' => 'required|exists:cuentas,id',
        'remito_id' => 'required|exists:remitos,id',
    ];

    public function updatingSearch() { $this->resetPage(); }

    public function render()
    {
        $query = Bien::with(['dependencia','proveedor','cuenta','remito']);

        // Filtro por búsqueda
        if ($this->search) {
            $query->where('numero_inventario', 'like', "%{$this->search}%")
                  ->orWhere('descripcion', 'like', "%{$this->search}%");
        }

        // Mostrar bajas o no
        if (!$this->mostrarBajas) {
            $query->where('estado', '!=', 'baja');
        }

        $bienes = $query->orderBy('numero_inventario')->paginate(8);

        return view('livewire.admin.bienes', [
            'bienes' => $bienes,
            'dependencias' => Dependencia::orderBy('nombre')->get(),
            'proveedores' => Proveedor::orderBy('razon_social')->get(),
            'cuentas' => Cuenta::orderBy('codigo')->get(),
            'remitos' => Remito::orderBy('numero_remito')->get(),
        ]);
    }

    public function guardarBien()
    {
        $this->validate();

        $total = $this->cantidad * $this->precio_unitario;

        Bien::create([
            'numero_inventario' => $this->numero_inventario,
            'descripcion' => $this->descripcion,
            'cantidad' => $this->cantidad,
            'precio_unitario' => $this->precio_unitario,
            'monto_total' => $total,
            'bien_uso' => $this->bien_uso ? 1 : 0,
            'bien_consumo' => $this->bien_consumo ? 1 : 0,
            'estado' => $this->estado,
            'dependencia_id' => $this->dependencia_id,
            'proveedor_id' => $this->proveedor_id,
            'cuenta_id' => $this->cuenta_id,
            'remito_id' => $this->remito_id,
        ]);

        $this->reset(['showModal','numero_inventario','descripcion','cantidad','precio_unitario',
                      'bien_uso','bien_consumo','estado','dependencia_id','proveedor_id','cuenta_id','remito_id']);

        session()->flash('message', 'Bien registrado correctamente ✅');
    }

    public function darDeBajaBien($id)
    {
        if ($b = Bien::find($id)) {
            $b->update(['estado' => 'baja']);
            session()->flash('message', 'Bien dado de baja 🚫');
        }
    }

    public function reactivarBien($id)
    {
        if ($b = Bien::find($id)) {
            $b->update(['estado' => 'stock']);
            session()->flash('message', 'Bien reactivado ✅');
        }
    }
}
