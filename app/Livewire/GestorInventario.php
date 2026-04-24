<?php

namespace App\Livewire;

use App\Models\Bien;
use App\Models\Asignacion;
use App\Models\Dependencia;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class GestorInventario extends Component
{
    // Bienes seleccionados
    public $bienesSeleccionados = [];
    
    // Datos para asignación
    public $dependencia_id = '';
    public $fecha_asignacion = '';
    public $observacion = '';
    
    // Vista de registros
    public $mostrarAsignaciones = false;
    public $asignaciones = [];
    
    // Modal para ver foto
    public $mostrarModalFoto = false;
    public $fotoUrl = '';
    public $bienSeleccionado = null;

    public function mount()
    {
        $this->fecha_asignacion = date('Y-m-d');
    }

    // Seleccionar/deseleccionar bien
    public function toggleBien($bienId)
    {
        if (in_array($bienId, $this->bienesSeleccionados)) {
            $this->bienesSeleccionados = array_diff($this->bienesSeleccionados, [$bienId]);
        } else {
            $this->bienesSeleccionados[] = $bienId;
        }
    }

    // Ver foto del bien
    public function verFotoBien($bienId)
    {
        $bien = Bien::find($bienId);

        if ($bien && $bien->foto) {
            $this->bienSeleccionado = $bien;
            $this->fotoUrl = asset('storage/' . $bien->foto);
            $this->mostrarModalFoto = true;
        } else {
            session()->flash('error', 'No hay foto disponible para este bien.');
        }
    }

    public function cerrarModalFoto()
    {
        $this->mostrarModalFoto = false;
        $this->fotoUrl = '';
        $this->bienSeleccionado = null;
    }

  public function asignarBienes()
{
    $this->validate([
        'dependencia_id' => 'required|exists:dependencias,id',
        'fecha_asignacion' => 'required|date',
        'bienesSeleccionados' => 'required|array|min:1',
    ], [
        'dependencia_id.required' => 'Debe seleccionar una dependencia destino',
        'bienesSeleccionados.required' => 'Debe seleccionar al menos un bien',
        'bienesSeleccionados.min' => 'Debe seleccionar al menos un bien',
    ]);

    $dependencia = Dependencia::find($this->dependencia_id);

    foreach ($this->bienesSeleccionados as $bienId) {
        $bien = Bien::with(['cuenta', 'proveedor', 'remito'])->find($bienId);

        if ($bien && $bien->estado === 'stock') {

            // Crear asignación
            Asignacion::create([
                'bien_id' => $bien->id,
                'dependencia_id' => $this->dependencia_id,
                'fecha_asignacion' => $this->fecha_asignacion,
                'user_id' => Auth::id(),
                'observacion' => $this->observacion,
            ]);

            // Actualizar estado del bien
            $bien->update([
                'estado' => 'asignado',
                'dependencia_id' => $this->dependencia_id,
            ]);

            /*
            |--------------------------------------------------------------------------
            | 📌 1) Construir TEXTO DE INFORMACIÓN COMPLETA DEL BIEN
            |--------------------------------------------------------------------------
            */

            $contenidoQR =
          
                "---------------------------\n" .
                "ID del Bien: {$bien->id}\n" .
                "N° Inventario: {$bien->numero_inventario}\n" .
                "Descripción: {$bien->descripcion}\n" .
                "Cantidad: {$bien->cantidad}\n" .
                "Estado Actual: {$bien->estado}\n\n" .

                "Cuenta Contable\n" .
                "Código: {$bien->cuenta->codigo}\n" .
                "Descripción: {$bien->cuenta->descripcion}\n\n" .

                "Remito / Documentación\n" .
                "N° Remito: " . ($bien->remito->numero_remito ?? 'No registrado') . "\n" .
                "N° Expediente: " . ($bien->remito->numero_expediente ?? 'No registrado') . "\n" .
                "Orden de Provisión: " . ($bien->remito->orden_provision ?? 'No registrado') . "\n" .
                "Fecha Recepción: " . ($bien->remito->fecha_recepcion ?? 'No registrada') . "\n\n" .

                " Proveedor\n" .
                "Razón Social: " . ($bien->proveedor->razon_social ?? 'No registrado') . "\n\n" .

                "Dependencia Asignada\n" .
                "Nombre: " . ($dependencia->nombre ?? 'Sin asignar') . "\n" .
                "Asignado el: {$this->fecha_asignacion}\n" .
                "Observación: " . ($this->observacion ?: 'Sin observaciones') . "\n";

            /*
            |--------------------------------------------------------------------------
            | 📌 2) Generar y guardar el QR
            |--------------------------------------------------------------------------
            */
            $rutaQR = "qrcodes/bien_{$bien->id}.svg";

            Storage::disk('public')->put(
                $rutaQR,
                QrCode::format('svg')
                    ->size(280)
                    ->encoding('UTF-8')
                    ->errorCorrection('H')
                    ->generate($contenidoQR)
            );

            // Guardar ruta del QR
            $bien->update(['codigo_qr' => $rutaQR]);
        }
    }

    session()->flash(
        'message',
        count($this->bienesSeleccionados) . ' bien(es) asignado(s) correctamente y se generó su código QR completo.'
    );

    // Reset
    $this->reset(['bienesSeleccionados', 'dependencia_id', 'observacion']);
    $this->fecha_asignacion = date('Y-m-d');
}


    public function verAsignaciones()
    {
        $this->asignaciones = Asignacion::with(['bien.cuenta', 'bien.proveedor', 'dependencia', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();
        
        $this->mostrarAsignaciones = true;
    }

    public function volverAlFormulario()
    {
        $this->mostrarAsignaciones = false;
    }

    public function cancelar()
    {
        $this->reset(['bienesSeleccionados', 'dependencia_id', 'observacion']);
        $this->fecha_asignacion = date('Y-m-d');
    }

    public function render()
    {
        return view('livewire.gestor-inventario-panel', [
            'bienesStock' => Bien::with(['cuenta', 'proveedor', 'remito'])
                ->where('estado', 'stock')
                ->orderBy('created_at', 'desc')
                ->get(),
            'dependencias' => Dependencia::where('activo', true)
                ->orderBy('codigo')
                ->get(),
        ])->layout('components.admin-layout', [
            'title' => 'Panel del Gestor de Inventario',
        ]);
    }
}
