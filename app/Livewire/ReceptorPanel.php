<?php

namespace App\Livewire;

use App\Models\Remito;
use Illuminate\Support\Facades\Auth;
use App\Models\Bien;
use App\Models\Cuenta;
use App\Models\Proveedor;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ReceptorPanel extends Component
{
    use WithFileUploads, WithPagination;

    public $formularios = [];
    public $fotos = [];
    public $numero_remito = '';
    public $numero_expediente = '';
    public $orden_provision = '';
    public $mostrarRegistros = false;

    // ✅ NUEVO (para modal editar)
    public $editandoBien = null;

    public $alerta_remito = '';
    public $alerta_expediente = '';
    public $alerta_orden = '';
    public $alertas_inventario = [];
    public $nextId = 1;

    protected $paginationTheme = 'tailwind';

    // ✅ NUEVO: listener para confirmar eliminación desde JS
    protected $listeners = [
        'eliminarBienConfirmado' => 'eliminarBien',
    ];

    public function mount()
    {
        $this->formularios = [$this->formularioVacio()];
    }

    private function formularioVacio()
    {
        return [
            'id' => $this->nextId++,
            'cuenta_id' => '',
            'proveedor_id' => '',
            'numero_inventario' => '',
            'descripcion' => '',
            'cantidad' => 1,
            'precio_unitario' => '',
            'monto_total' => 0,
            'fecha_recepcion' => now()->format('Y-m-d'),
            'tipo_bien' => 'uso',
            'compra_licitacion' => false,
        ];
    }

    public function updatedNumeroRemito($value)
    {
        $this->alerta_remito = Remito::where('numero_remito', trim($value))->exists()
            ? 'Este número de remito ya existe.'
            : '';
    }

    public function updatedNumeroExpediente($value)
    {
        $this->alerta_expediente = Remito::where('numero_expediente', trim($value))->exists()
            ? 'Este número de expediente ya existe.'
            : '';
    }

    public function updatedOrdenProvision($value)
    {
        $this->alerta_orden = Remito::where('orden_provision', trim($value))->exists()
            ? 'Esta orden de provisión ya existe.'
            : '';
    }

    public function updatedFormularios($value, $key)
    {
        $parts = explode('.', $key);
        if (count($parts) < 2) return;

        $index = $parts[0];
        $campo = $parts[1];

        if ($campo === 'numero_inventario' && $value !== '') {
            $existe = Bien::where('numero_inventario', $value)->exists();
            if ($existe) {
                $this->alertas_inventario[$index] = 'Número de inventario ya existente.';
            } else {
                unset($this->alertas_inventario[$index]);
            }
        }

        if (in_array($campo, ['precio_unitario', 'cantidad'])) {
            $precio = floatval($this->formularios[$index]['precio_unitario'] ?? 0);
            $cantidad = intval($this->formularios[$index]['cantidad'] ?? 1);
            $this->formularios[$index]['monto_total'] = $precio * $cantidad;
        }
    }

    public function agregarFormulario()
    {
        $this->formularios[] = $this->formularioVacio();
        $this->dispatch('formulario-agregado');
    }

    public function eliminarFormulario($id)
    {
        $this->formularios = array_values(
            array_filter($this->formularios, fn($f) => $f['id'] !== $id)
        );
        unset($this->fotos[$id]);
        unset($this->alertas_inventario[$id]);
    }

    public function registrarBien()
    {
        if (
            $this->alerta_remito ||
            $this->alerta_expediente ||
            $this->alerta_orden ||
            !empty($this->alertas_inventario)
        ) {
            session()->flash('error', 'Corrija los datos duplicados.');
            $this->dispatch('toast', message: 'Corrija los datos duplicados.', type: 'danger');
            return;
        }

        $this->validate([
            'numero_remito' => 'required',
            'formularios.*.cuenta_id' => 'required|exists:cuentas,id',
            'formularios.*.proveedor_id' => 'required|exists:proveedores,id',
            'formularios.*.numero_inventario' => 'required|numeric|min:1',
            'formularios.*.descripcion' => 'required',
            'formularios.*.cantidad' => 'required|integer|min:1',
            'formularios.*.precio_unitario' => 'required|numeric|min:0',
            'formularios.*.fecha_recepcion' => 'required|date',
            'fotos.*' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            foreach ($this->formularios as $form) {
                for ($i = 0; $i < $form['cantidad']; $i++) {
                    $num = intval($form['numero_inventario']) + $i;
                    if (Bien::where('numero_inventario', $num)->exists()) {
                        throw new \Exception("Inventario $num ya existe.");
                    }
                }
            }

            $remito = Remito::firstOrCreate(
                ['numero_remito' => $this->numero_remito],
                [
                    'numero_expediente' => $this->numero_expediente,
                    'orden_provision' => $this->orden_provision,
                    'fecha_recepcion' => $this->formularios[0]['fecha_recepcion'],
                    'tipo_compra' => $this->formularios[0]['compra_licitacion'] ? 'licitacion' : 'directa',
                    'proveedor_id' => $this->formularios[0]['proveedor_id'],
                    'user_id' => Auth::id(),
                ]
            );

            foreach ($this->formularios as $form) {
                $foto = null;
                if (!empty($this->fotos[$form['id']])) {
                    $foto = $this->fotos[$form['id']]->store('bienes', 'public');
                }

                for ($i = 0; $i < $form['cantidad']; $i++) {
                    Bien::create([
                        'cuenta_id' => $form['cuenta_id'],
                        'proveedor_id' => $form['proveedor_id'],
                        'remito_id' => $remito->id,
                        'numero_inventario' => intval($form['numero_inventario']) + $i,
                        'descripcion' => $form['descripcion'],
                        'cantidad' => 1,
                        'precio_unitario' => $form['precio_unitario'],
                        'monto_total' => $form['precio_unitario'],
                        'bien_uso' => $form['tipo_bien'] === 'uso',
                        'bien_consumo' => $form['tipo_bien'] === 'consumo',
                        'estado' => 'stock',
                        'foto' => $foto,
                    ]);
                }
            }

            DB::commit();

            session()->flash('message', '✅ Bienes registrados correctamente.');
            $this->dispatch('toast', message: '✅ Bienes registrados correctamente.', type: 'success');

            $this->limpiar();
            $this->mostrarRegistros = false;

        } catch (\Exception $e) {
            DB::rollBack();

            session()->flash('error', $e->getMessage());
            $this->dispatch('toast', message: $e->getMessage(), type: 'danger');
        }
    }

    private function limpiar()
    {
        $this->nextId = 1;
        $this->formularios = [$this->formularioVacio()];
        $this->fotos = [];
        $this->alertas_inventario = [];
        $this->numero_remito = '';
        $this->numero_expediente = '';
        $this->orden_provision = '';
        $this->alerta_remito = '';
        $this->alerta_expediente = '';
        $this->alerta_orden = '';

        // ✅ NUEVO: cerrar modal si estaba abierto
        $this->editandoBien = null;
    }

    public function cancelar()
    {
        $this->limpiar();
        $this->mostrarRegistros = false;

        session()->flash('message', 'Registro cancelado');
        $this->dispatch('toast', message: 'Registro cancelado', type: 'warning');
    }

    public function verRegistros()
    {
        $this->mostrarRegistros = true;
        $this->resetPage();
    }

    public function volverAlFormulario()
    {
        $this->mostrarRegistros = false;
        $this->resetPage();
    }

    // =========================
    // ✅ NUEVO: EDITAR / GUARDAR
    // =========================
    public function editarBien($id)
{
    $bien = Bien::with(['proveedor', 'cuenta', 'remito'])->findOrFail($id);

    $this->editandoBien = [
        'id' => $bien->id,

        // 🔎 Datos visibles (NO editables)
        'numero_remito' => $bien->remito->numero_remito ?? '',
        'numero_expediente' => $bien->remito->numero_expediente ?? '',
        'orden_provision' => $bien->remito->orden_provision ?? '',
        'numero_inventario' => $bien->numero_inventario,

        // ✏️ Editables
        'descripcion' => $bien->descripcion,
        'cuenta_id' => $bien->cuenta_id,
        'proveedor_id' => $bien->proveedor_id,
        'fecha_recepcion' => $bien->remito->fecha_recepcion ?? null,
        'tipo_bien' => $bien->bien_uso ? 'uso' : 'consumo',
        'compra_licitacion' => $bien->remito->tipo_compra === 'licitacion',

        // 🔒 Bloqueados
        'cantidad' => $bien->cantidad,
        'precio_unitario' => $bien->precio_unitario,
        'monto_total' => $bien->monto_total,
    ];
}


    public function guardarEdicion()
{
    if (!$this->editandoBien || empty($this->editandoBien['id'])) {
        session()->flash('error', 'No hay un bien seleccionado.');
        return;
    }

    $this->validate([
        'editandoBien.descripcion' => 'required|string',
        'editandoBien.cuenta_id' => 'required|exists:cuentas,id',
        'editandoBien.proveedor_id' => 'required|exists:proveedores,id',
        'editandoBien.fecha_recepcion' => 'required|date',
        'editandoBien.tipo_bien' => 'required|in:uso,consumo',
    ]);

    $bien = Bien::findOrFail($this->editandoBien['id']);

    // ✅ solo campos permitidos
    $bien->update([
        'descripcion' => $this->editandoBien['descripcion'],
        'cuenta_id' => $this->editandoBien['cuenta_id'],
        'proveedor_id' => $this->editandoBien['proveedor_id'],
        'bien_uso' => $this->editandoBien['tipo_bien'] === 'uso',
        'bien_consumo' => $this->editandoBien['tipo_bien'] === 'consumo',
    ]);

    // actualizar remito asociado
    if ($bien->remito) {
        $bien->remito->update([
            'fecha_recepcion' => $this->editandoBien['fecha_recepcion'],
            'tipo_compra' => $this->editandoBien['compra_licitacion']
                ? 'licitacion'
                : 'directa',
        ]);
    }

    session()->flash('message', '✅ Bien actualizado correctamente.');
    $this->editandoBien = null;
}


    // =========================
    // ✅ NUEVO: ELIMINAR
    // =========================
    public function eliminarBien($id)
    {
        try {
            $bien = Bien::findOrFail($id);
            $bien->delete();

            session()->flash('message', '✅ Bien eliminado correctamente.');
            $this->dispatch('toast', message: '✅ Bien eliminado correctamente.', type: 'success');

            // si estamos en registros, que no se rompa la paginación al borrar el último
            if ($this->mostrarRegistros && $this->page > 1 && Bien::count() <= ($this->page - 1) * 5) {
                $this->previousPage();
            }
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            $this->dispatch('toast', message: $e->getMessage(), type: 'danger');
        }
    }

    public function render()
    {
        $bienes = Bien::with(['proveedor', 'cuenta', 'remito'])
            ->latest()
            ->paginate(5);

        return view('livewire.receptor-panel', [
            'cuentas' => Cuenta::where('activo', true)->orderBy('codigo')->get(),
            'proveedores' => Proveedor::where('estado', 1)->orderBy('razon_social')->get(),
            'bienes' => $bienes,
        ])->layout('components.admin-layout', ['title' => 'Panel del Receptor']);
    }
}
