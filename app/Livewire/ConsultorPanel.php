<?php

namespace App\Livewire;

use App\Models\Bien;
use App\Models\Cuenta;
use Livewire\Component;
use Livewire\WithPagination;

class ConsultorPanel extends Component
{
    use WithPagination;

    public $busqueda = '';
    public $bienSeleccionado = null;

    public $filtroEstado = '';
    public $filtroCuenta = '';

    public $vistaActual = 'tarjetas'; // 'tarjetas' o 'detalle'

    public $mostrarModalQR = false;
    public $qrActual = '';

    protected $paginationTheme = 'tailwind';
public function mount()
{
    // Si el QR contiene ?id=###
    if (request()->has('id')) {
        $bienId = request()->get('id');
        $this->bienSeleccionado = $bienId;
        $this->vistaActual = 'detalle';
    }
}


    // 🔹 Se ejecuta automáticamente al cambiar un filtro
    public function updatingFiltroEstado()
    {
        $this->resetPage();
    }

    public function updatingFiltroCuenta()
    {
        $this->resetPage();
    }

    // 🔹 Buscar un bien por inventario, descripción o cuenta contable
    public function buscar()
    {
        if (empty($this->busqueda)) {
            $this->bienSeleccionado = null;
            $this->vistaActual = 'tarjetas';
            return;
        }

        $bien = Bien::with(['cuenta', 'remito', 'proveedor', 'dependencia', 'documentacion'])
            ->where(function ($query) {
                $query->where('numero_inventario', 'like', '%' . $this->busqueda . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->busqueda . '%')
                      ->orWhereHas('cuenta', function ($q) {
                          $q->where('codigo', 'like', '%' . $this->busqueda . '%');
                      });
            })
            ->first();

        if ($bien) {
            $this->bienSeleccionado = $bien->id;
            $this->vistaActual = 'detalle';
            session()->flash('message', '✅ Bien encontrado');
        } else {
            session()->flash('error', '❌ No se encontró ningún bien con ese criterio');
            $this->bienSeleccionado = null;
        }
    }

    public function verDetalle($bienId)
    {
        $this->bienSeleccionado = $bienId;
        $this->vistaActual = 'detalle';

        $bien = Bien::find($bienId);
        if ($bien) {
            $this->busqueda = $bien->numero_inventario;
        }
    }

    public function volverATarjetas()
    {
        $this->vistaActual = 'tarjetas';
        $this->bienSeleccionado = null;
        $this->busqueda = '';
        $this->resetPage();
    }

    public function mostrarQR($bienId)
    {
        $bien = Bien::find($bienId);

        if ($bien && $bien->codigo_qr) {
            $this->qrActual = asset('storage/' . $bien->codigo_qr);
            $this->mostrarModalQR = true;
        } else {
            session()->flash('error', 'Este bien no tiene código QR generado');
        }
    }

    public function cerrarModalQR()
    {
        $this->mostrarModalQR = false;
        $this->qrActual = '';
    }

    public function simularEscaneoQR()
    {
        $this->dispatch('abrirCamara');
    }

    public function limpiarFiltros()
    {
        $this->filtroEstado = '';
        $this->filtroCuenta = '';
        $this->resetPage();
    }

    // 🔹 Propiedad computada para los bienes filtrados
    public function getBienesProperty()
    {
        $query = Bien::with(['cuenta', 'dependencia', 'remito', 'documentacion']);

        if ($this->busqueda) {
            $query->where(function ($q) {
                $q->where('numero_inventario', 'like', '%' . $this->busqueda . '%')
                  ->orWhere('descripcion', 'like', '%' . $this->busqueda . '%')
                  ->orWhereHas('cuenta', function ($sub) {
                      $sub->where('codigo', 'like', '%' . $this->busqueda . '%');
                  });
            });
        }

        if ($this->filtroEstado) {
            $query->where('estado', $this->filtroEstado);
        }

        if ($this->filtroCuenta) {
            $query->where('cuenta_id', $this->filtroCuenta);
        }

        return $query->orderBy('created_at', 'desc')->paginate(12);
    }

    // 🔹 Propiedad computada para el detalle de un bien
    public function getBienDetalleProperty()
    {
        if (!$this->bienSeleccionado) {
            return null;
        }

        return Bien::with([
            'cuenta',
            'remito',
            'proveedor',
            'dependencia',
            'documentacion.proveedor',
            'asignaciones.dependencia',
            'asignaciones.user'
        ])->find($this->bienSeleccionado);
    }

    public function render()
    {
        return view('livewire.consultor-panel', [
            'cuentas' => Cuenta::where('activo', true)->orderBy('codigo')->get(),
        ])->layout('components.admin-layout', [
            'title' => 'Panel de Consultor',
        ]);
    }
}
