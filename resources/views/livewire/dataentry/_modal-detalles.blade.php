{{-- resources/views/livewire/dataentry/_modal-detalles.blade.php --}}
@if($modalDetalles && $bienDetalle)
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    wire:click="cerrarModal('detalles')">

    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 p-6 overflow-y-auto max-h-[90vh]"
        wire:click.stop>

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
                <x-heroicon-o-information-circle class="w-6 h-6 text-indigo-600" />
                Detalles del Bien
            </h3>
            <button wire:click="cerrarModal('detalles')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Foto -->
        <div class="flex justify-center mb-6">
            @if($bienDetalle->remito && $bienDetalle->remito->foto_remito)
                <img src="{{ asset('storage/' . $bienDetalle->remito->foto_remito) }}"
                     alt="Foto del remito {{ $bienDetalle->remito->numero_remito }}"
                     class="max-h-64 rounded-lg shadow-md border object-contain hover:scale-105 transition-transform duration-300"
                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'text-center text-red-500\'><svg class=\'w-10 h-10 mx-auto mb-2\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z\'/></svg><p class=\'text-sm\'>Error: Imagen no encontrada</p></div>';">
            @else
                <div class="flex flex-col items-center text-gray-500">
                    <svg class="w-10 h-10 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 5h18M3 19h18M5 5v14M19 5v14M9 10l3 3 3-3" />
                    </svg>
                    <p>No hay foto cargada para este bien.</p>
                </div>
            @endif
        </div>

        <!-- Sección Remito -->
        <h4 class="text-lg font-semibold text-indigo-700 border-b border-indigo-200 pb-1 mb-3">Remito</h4>
        <table class="w-full text-sm border border-gray-200 rounded-lg mb-6">
            <tbody class="divide-y divide-gray-200">
                <tr>
                    <td class="font-semibold bg-gray-50 w-56 p-2">Bien (Cuenta):</td>
                    <td class="p-2">
                        @if($bienDetalle->cuenta)
                            {{ $bienDetalle->cuenta->codigo }} - {{ $bienDetalle->cuenta->descripcion }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
                <tr><td class="font-semibold bg-gray-50 p-2">N° Inventario:</td><td class="p-2">{{ $bienDetalle->numero_inventario }}</td></tr>
                <tr><td class="font-semibold bg-gray-50 p-2">N° Remito:</td><td class="p-2">{{ $bienDetalle->remito->numero_remito ?? 'N/A' }}</td></tr>
                <tr><td class="font-semibold bg-gray-50 p-2">N° Expediente:</td><td class="p-2">{{ $bienDetalle->remito->numero_expediente ?? 'N/A' }}</td></tr>
                <tr><td class="font-semibold bg-gray-50 p-2">N° Provisión:</td><td class="p-2">{{ $bienDetalle->remito->orden_provision ?? 'N/A' }}</td></tr>
                <tr><td class="font-semibold bg-gray-50 p-2">Fecha Recepción:</td><td class="p-2">{{ $bienDetalle->remito->fecha_recepcion ? \Carbon\Carbon::parse($bienDetalle->remito->fecha_recepcion)->format('d/m/Y') : 'N/A' }}</td></tr>
                <tr><td class="font-semibold bg-gray-50 p-2">Proveedor:</td><td class="p-2">{{ $bienDetalle->proveedor->razon_social ?? 'N/A' }}</td></tr>
                <tr><td class="font-semibold bg-gray-50 p-2">Tipo de Bien:</td><td class="p-2">{{ $bienDetalle->bien_uso ? 'Bien de Uso' : 'Bien de Consumo' }}</td></tr>
                <tr><td class="font-semibold bg-gray-50 p-2">Tipo de Compra:</td><td class="p-2">{{ $bienDetalle->remito->tipo_compra ?? 'N/A' }}</td></tr>
                <tr><td class="font-semibold bg-gray-50 p-2">Descripción del Bien:</td><td class="p-2">{{ $bienDetalle->descripcion }}</td></tr>
                <tr><td class="font-semibold bg-gray-50 p-2">Precio Unitario:</td><td class="p-2">${{ number_format($bienDetalle->precio_unitario ?? 0, 2, ',', '.') }}</td></tr>
                <tr><td class="font-semibold bg-gray-50 p-2">Precio Total:</td><td class="p-2">${{ number_format($bienDetalle->monto_total ?? 0, 2, ',', '.') }}</td></tr>
            </tbody>
        </table>

        <!-- Sección Documentación -->
        <h4 class="text-lg font-semibold text-indigo-700 border-b border-indigo-200 pb-1 mb-3">Documentación Asociada</h4>
        <table class="w-full text-sm border border-gray-200 rounded-lg">
            <tbody class="divide-y divide-gray-200">
                <tr><td class="font-semibold bg-gray-50 w-56 p-2">N° de Acta:</td><td class="p-2">{{ $bienDetalle->documentacion->numero_acta ?? 'N/A' }}</td></tr>
                <tr><td class="font-semibold bg-gray-50 p-2">Fecha de Acta:</td><td class="p-2">{{ $bienDetalle->documentacion->fecha_acta ? \Carbon\Carbon::parse($bienDetalle->documentacion->fecha_acta)->format('d/m/Y') : 'N/A' }}</td></tr>
                <tr><td class="font-semibold bg-gray-50 p-2">N° de Factura:</td><td class="p-2">{{ $bienDetalle->documentacion->numero_factura ?? 'N/A' }}</td></tr>
                <tr><td class="font-semibold bg-gray-50 p-2">Fecha de Factura:</td><td class="p-2">{{ $bienDetalle->documentacion->fecha_factura ? \Carbon\Carbon::parse($bienDetalle->documentacion->fecha_factura)->format('d/m/Y') : 'N/A' }}</td></tr>
                <tr><td class="font-semibold bg-gray-50 p-2">Resolución:</td><td class="p-2">{{ $bienDetalle->documentacion->numero_resolucion ?? 'N/A' }}</td></tr>
                <tr><td class="font-semibold bg-gray-50 p-2">Partida Presupuestaria:</td><td class="p-2">{{ $bienDetalle->documentacion->partida_presupuestaria ?? 'N/A' }}</td></tr>
                <tr><td class="font-semibold bg-gray-50 p-2">Orden de Pago:</td><td class="p-2">{{ $bienDetalle->documentacion->orden_pago ?? 'N/A' }}</td></tr>
                <tr>
                    <td class="font-semibold bg-gray-50 p-2">Estado:</td>
                    <td class="p-2">
                        <span class="px-2 py-1 rounded text-xs
                            @if(($bienDetalle->documentacion->estado ?? '') === 'completo') bg-green-100 text-green-800
                            @elseif(($bienDetalle->documentacion->estado ?? '') === 'revisado') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($bienDetalle->documentacion->estado ?? 'Pendiente') }}
                        </span>
                    </td>
                </tr>
                <tr><td class="font-semibold bg-gray-50 p-2">Observaciones:</td><td class="p-2">{{ $bienDetalle->documentacion->observaciones ?? '—' }}</td></tr>
            </tbody>
        </table>

        <div class="mt-6 flex justify-end">
            <button wire:click="cerrarModal('detalles')"
                class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                Cerrar
            </button>
        </div>
    </div>
</div>
@endif