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
    public $fotos_remito = [];
    public $numero_remito = '';
    public $numero_expediente = '';
    public $orden_provision = '';
    public $mostrarRegistros = false;

    public $editandoBien = null;

    public $alerta_remito = '';
    public $alerta_expediente = '';
    public $alerta_orden = '';
    public $alertas_inventario = [];
    public $nextId = 1;

    protected $paginationTheme = 'tailwind';

    protected $listeners = [
        'eliminarBienConfirmado' => 'eliminarBien',
    ];

    public function mount()
    {
        $this->formularios = [$this->formularioVacio()];
    }

    // ─────────────────────────────────────────────
    // FORMULARIO
    // ─────────────────────────────────────────────

    private function formularioVacio(): array
    {
        return [
            'id'                => $this->nextId++,
            'cuenta_id'         => '',
            'proveedor_id'      => '',
            'numero_inventario' => '',
            'descripcion'       => '',
            'cantidad'          => 1,
            'precio_unitario'   => '',
            'monto_total'       => 0,
            'fecha_recepcion'   => now()->format('Y-m-d'),
            'tipo_bien'         => 'uso',
            'compra_licitacion' => false,
            'empleado_recibe'   => '',
        ];
    }

    public function agregarFormulario(): void
    {
        $this->formularios[] = $this->formularioVacio();
        $this->dispatch('formulario-agregado');
    }

    public function eliminarFormulario(int $id): void
    {
        $this->formularios = array_values(
            array_filter($this->formularios, fn($f) => $f['id'] !== $id)
        );
        unset($this->fotos_remito[$id]);
        unset($this->alertas_inventario[$id]);
    }

    // ─────────────────────────────────────────────
    // WATCHERS — validaciones en tiempo real
    // ─────────────────────────────────────────────

    public function updatedNumeroRemito($value): void
    {
        $this->alerta_remito = Remito::where('numero_remito', trim($value))->exists()
            ? 'Este número de remito ya existe.'
            : '';
    }

    public function updatedNumeroExpediente($value): void
    {
        $this->alerta_expediente = Remito::where('numero_expediente', trim($value))->exists()
            ? 'Este número de expediente ya existe.'
            : '';
    }

    public function updatedOrdenProvision($value): void
    {
        $this->alerta_orden = Remito::where('orden_provision', trim($value))->exists()
            ? 'Esta orden de provisión ya existe.'
            : '';
    }

    public function updatedFormularios($value, $key): void
    {
        $parts = explode('.', $key);
        if (count($parts) < 2) return;

        [$index, $campo] = [$parts[0], $parts[1]];

        // Validar inventario duplicado (se dispara con wire:model.blur)
        if ($campo === 'numero_inventario' && $value !== '') {
            $existe = Bien::where('numero_inventario', $value)->exists();
            if ($existe) {
                $this->alertas_inventario[$index] = 'Número de inventario ya existente.';
            } else {
                unset($this->alertas_inventario[$index]);
            }
        }

        // Recalcular monto total
        if (in_array($campo, ['precio_unitario', 'cantidad'])) {
            $precio   = floatval($this->formularios[$index]['precio_unitario'] ?? 0);
            $cantidad = intval($this->formularios[$index]['cantidad'] ?? 1);
            $this->formularios[$index]['monto_total'] = $precio * $cantidad;
        }
    }

    // ─────────────────────────────────────────────
    // REGISTRO
    // ─────────────────────────────────────────────

    public function registrarBien(): void
    {
        // Validación de duplicados previos
        if ($this->alerta_remito || $this->alerta_expediente || $this->alerta_orden || !empty($this->alertas_inventario)) {
            $mensajes = array_filter([
                $this->alerta_remito,
                $this->alerta_expediente,
                $this->alerta_orden,
                ...array_values($this->alertas_inventario),
            ]);

            $this->dispatch('show-alert',
                title: 'Datos duplicados',
                text: implode('||', $mensajes),
                icon: 'error'
            );
            return;
        }

        // Validación Laravel
        try {
            $this->validate([
                'numero_remito'                   => 'required|string|max:50',
                'numero_expediente'               => 'required|string|max:50',
                'orden_provision'                 => 'required|string|max:50',
                'formularios.*.cuenta_id'         => 'required|exists:cuentas,id',
                'formularios.*.proveedor_id'      => 'required|exists:proveedores,id',
                'formularios.*.numero_inventario' => 'required|string|max:50',
                'formularios.*.descripcion'       => 'required|string|min:3',
                'formularios.*.cantidad'          => 'required|integer|min:1',
                'formularios.*.precio_unitario'   => 'required|numeric|min:0',
                'formularios.*.fecha_recepcion'   => 'required|date',
                'formularios.*.tipo_bien'         => 'required|in:uso,consumo',
                'formularios.*.empleado_recibe'   => 'required|string|max:255',
                'fotos_remito.*'                  => 'nullable|image|max:10240',
            ], [
                'numero_remito.required'                   => 'Falta completar el número de remito.',
                'numero_expediente.required'               => 'Falta completar el número de expediente.',
                'orden_provision.required'                 => 'Falta completar la orden de provisión.',
                'formularios.*.cuenta_id.required'         => 'Falta seleccionar la cuenta del bien.',
                'formularios.*.cuenta_id.exists'           => 'La cuenta seleccionada no es válida.',
                'formularios.*.proveedor_id.required'      => 'Falta seleccionar el proveedor.',
                'formularios.*.proveedor_id.exists'        => 'El proveedor seleccionado no es válido.',
                'formularios.*.numero_inventario.required' => 'Falta completar el número de inventario.',
                'formularios.*.descripcion.required'       => 'Falta completar la descripción del bien.',
                'formularios.*.descripcion.min'            => 'La descripción es muy corta, mínimo 3 caracteres.',
                'formularios.*.cantidad.required'          => 'Falta completar la cantidad.',
                'formularios.*.cantidad.min'               => 'La cantidad debe ser al menos 1.',
                'formularios.*.precio_unitario.required'   => 'Falta completar el precio unitario.',
                'formularios.*.precio_unitario.min'        => 'El precio unitario no puede ser negativo.',
                'formularios.*.fecha_recepcion.required'   => 'Falta completar la fecha de recepción.',
                'formularios.*.tipo_bien.required'         => 'Falta seleccionar el tipo de bien.',
                'formularios.*.empleado_recibe.required'   => 'Falta completar el nombre del empleado que recibe.',
                'fotos_remito.*.image'                     => 'El archivo de foto debe ser una imagen.',
                'fotos_remito.*.max'                       => 'La imagen no puede superar los 10MB.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errores = collect($e->validator->errors()->all())
                ->unique()
                ->values()
                ->implode('||');

            $this->dispatch('show-alert',
                title: 'Faltan datos obligatorios',
                text: $errores,
                icon: 'error'
            );
            return;
        }

        DB::beginTransaction();

        try {
            // Verificar inventarios duplicados en BD antes de insertar
            foreach ($this->formularios as $form) {
                $inventarioBase = intval($form['numero_inventario']);
                for ($i = 0; $i < $form['cantidad']; $i++) {
                    $numInventario = $inventarioBase + $i;
                    if (Bien::where('numero_inventario', $numInventario)->exists()) {
                        throw new \Exception("El número de inventario {$numInventario} ya existe.");
                    }
                }
            }

            // Crear remito
            $remito = Remito::where('numero_remito', trim($this->numero_remito))->first();

            if (!$remito) {
                $remito = Remito::create([
                    'numero_remito'     => trim($this->numero_remito),
                    'numero_expediente' => trim($this->numero_expediente),
                    'orden_provision'   => trim($this->orden_provision),
                    'fecha_recepcion'   => $this->formularios[0]['fecha_recepcion'],
                    'tipo_compra'       => $this->formularios[0]['compra_licitacion'] ? 'licitacion' : 'directa',
                    'proveedor_id'      => $this->formularios[0]['proveedor_id'],
                    'user_id'           => Auth::id(),
                ]);
            }

            $totalBienesCreados = 0;

            foreach ($this->formularios as $form) {
                // Guardar foto si existe
                $fotoRemito = null;
                if (!empty($this->fotos_remito[$form['id']])) {
                    $file          = $this->fotos_remito[$form['id']];
                    $nombreArchivo = time() . '_' . uniqid() . '_remito.' . $file->getClientOriginalExtension();
                    $fotoRemito    = $file->storeAs('remitos', $nombreArchivo, 'public');
                }

                // Crear un bien por cada unidad de cantidad
                $inventarioBase = intval($form['numero_inventario']);
                $precioUnitario = floatval($form['precio_unitario']);

                for ($i = 0; $i < $form['cantidad']; $i++) {
                    Bien::create([
                        'cuenta_id'         => $form['cuenta_id'],
                        'proveedor_id'      => $form['proveedor_id'],
                        'remito_id'         => $remito->id,
                        'numero_inventario' => $inventarioBase + $i,
                        'descripcion'       => $form['descripcion'],
                        'cantidad'          => 1,
                        'precio_unitario'   => $precioUnitario,
                        'monto_total'       => $precioUnitario, // cantidad siempre 1 por bien individual
                        'bien_uso'          => $form['tipo_bien'] === 'uso',
                        'bien_consumo'      => $form['tipo_bien'] === 'consumo',
                        'estado'            => 'stock',
                        'foto_remito'       => $fotoRemito,
                        'empleado_recibe'   => $form['empleado_recibe'],
                    ]);

                    $totalBienesCreados++;
                }
            }

            DB::commit();

            $this->dispatch('show-alert',
                title: '¡Registro exitoso!',
                text: "Se registraron {$totalBienesCreados} bien(es) correctamente.||Remito N°: {$this->numero_remito}",
                icon: 'success'
            );

            $this->limpiar();

        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('show-alert',
                title: 'Error al registrar',
                text: 'Ocurrió un error: ' . $e->getMessage(),
                icon: 'error'
            );
        }
    }

    // ─────────────────────────────────────────────
    // EDICIÓN
    // ─────────────────────────────────────────────

    public function editarBien(int $id): void
    {
        $bien = Bien::with(['proveedor:id,razon_social', 'cuenta:id,codigo', 'remito:id,numero_remito,numero_expediente,orden_provision,fecha_recepcion,tipo_compra'])
            ->findOrFail($id);

        $this->editandoBien = [
            'id'                => $bien->id,
            'numero_remito'     => $bien->remito->numero_remito ?? '',
            'numero_expediente' => $bien->remito->numero_expediente ?? '',
            'orden_provision'   => $bien->remito->orden_provision ?? '',
            'numero_inventario' => $bien->numero_inventario,
            'descripcion'       => $bien->descripcion,
            'cuenta_id'         => $bien->cuenta_id,
            'proveedor_id'      => $bien->proveedor_id,
            'fecha_recepcion'   => $bien->remito->fecha_recepcion ?? null,
            'tipo_bien'         => $bien->bien_uso ? 'uso' : 'consumo',
            'compra_licitacion' => $bien->remito->tipo_compra === 'licitacion',
            'cantidad'          => $bien->cantidad,
            'precio_unitario'   => $bien->precio_unitario,
            'monto_total'       => $bien->monto_total,
            'empleado_recibe'   => $bien->empleado_recibe ?? '',
        ];
    }

    public function guardarEdicion(): void
    {
        if (!$this->editandoBien || empty($this->editandoBien['id'])) {
            session()->flash('error', 'No hay un bien seleccionado.');
            return;
        }

        $this->validate([
            'editandoBien.descripcion'     => 'required|string',
            'editandoBien.cuenta_id'       => 'required|exists:cuentas,id',
            'editandoBien.proveedor_id'    => 'required|exists:proveedores,id',
            'editandoBien.fecha_recepcion' => 'required|date',
            'editandoBien.tipo_bien'       => 'required|in:uso,consumo',
            'editandoBien.empleado_recibe' => 'required|string|max:255',
        ]);

        $bien = Bien::findOrFail($this->editandoBien['id']);

        $bien->update([
            'descripcion'     => $this->editandoBien['descripcion'],
            'cuenta_id'       => $this->editandoBien['cuenta_id'],
            'proveedor_id'    => $this->editandoBien['proveedor_id'],
            'bien_uso'        => $this->editandoBien['tipo_bien'] === 'uso',
            'bien_consumo'    => $this->editandoBien['tipo_bien'] === 'consumo',
            'empleado_recibe' => $this->editandoBien['empleado_recibe'],
        ]);

        if ($bien->remito) {
            $bien->remito->update([
                'fecha_recepcion' => $this->editandoBien['fecha_recepcion'],
                'tipo_compra'     => $this->editandoBien['compra_licitacion'] ? 'licitacion' : 'directa',
            ]);
        }

        $this->dispatch('show-alert',
            title: '¡Actualizado!',
            text: 'El bien ha sido actualizado correctamente.',
            icon: 'success'
        );

        $this->editandoBien = null;
    }

    // ─────────────────────────────────────────────
    // ELIMINACIÓN
    // ─────────────────────────────────────────────

    public function eliminarBien(int $id): void
    {
        try {
            $bien             = Bien::findOrFail($id);
            $numeroInventario = $bien->numero_inventario;
            $bien->delete();

            $this->dispatch('show-alert',
                title: '¡Eliminado!',
                text: "El bien con inventario N° {$numeroInventario} ha sido eliminado correctamente.",
                icon: 'success'
            );

            // Corrección bug Livewire v3: usar getPage() en lugar de $this->page
            if ($this->mostrarRegistros) {
                $currentPage = $this->getPage();
                if ($currentPage > 1 && Bien::count() <= ($currentPage - 1) * 5) {
                    $this->previousPage();
                }
            }
        } catch (\Exception $e) {
            $this->dispatch('show-alert',
                title: 'Error al Eliminar',
                text: 'Ocurrió un error: ' . $e->getMessage(),
                icon: 'error'
            );
        }
    }

    // ─────────────────────────────────────────────
    // NAVEGACIÓN
    // ─────────────────────────────────────────────

    public function verRegistros(): void
    {
        $this->mostrarRegistros = true;
        $this->resetPage();
    }

    public function volverAlFormulario(): void
    {
        $this->mostrarRegistros = false;
        $this->resetPage();
    }

    public function cancelar(): void
    {
        $this->limpiar();
        $this->mostrarRegistros = false;
    }

    // ─────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────

    private function limpiar(): void
    {
        $this->nextId           = 1;
        $this->formularios      = [$this->formularioVacio()];
        $this->fotos_remito     = [];
        $this->alertas_inventario = [];
        $this->numero_remito    = '';
        $this->numero_expediente = '';
        $this->orden_provision  = '';
        $this->alerta_remito    = '';
        $this->alerta_expediente = '';
        $this->alerta_orden     = '';
        $this->editandoBien     = null;
        $this->mostrarRegistros = false;
    }

    // ─────────────────────────────────────────────
    // RENDER
    // ─────────────────────────────────────────────

    public function render()
    {
        // without() evita cargar el $with del modelo con todas las columnas
        // y las reemplaza por versiones livianas con solo las columnas necesarias
        $bienes = Bien::without(['cuenta', 'proveedor', 'remito'])
            ->with([
                'proveedor:id,razon_social',
                'cuenta:id,codigo',
                'remito:id,numero_remito,numero_expediente,orden_provision,fecha_recepcion',
            ])
            ->latest()
            ->paginate(5);

        return view('livewire.receptor.index', [
            // Solo columnas necesarias para los selects — menos payload
            'cuentas'     => Cuenta::where('activo', true)
                ->orderBy('codigo')
                ->get(['id', 'codigo', 'descripcion']),
            'proveedores' => Proveedor::where('estado', 1)
                ->orderBy('razon_social')
                ->get(['id', 'razon_social']),
            'bienes'      => $bienes,
        ])->layout('components.admin-layout', ['title' => 'Panel del Receptor']);
    }
}