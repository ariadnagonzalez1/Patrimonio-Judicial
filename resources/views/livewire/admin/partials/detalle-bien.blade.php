@if ($modalDetalle && $bienSeleccionado)
<div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">

    {{-- CONTENEDOR DEL MODAL --}}
    <div class="bg-white w-full max-w-5xl rounded-xl shadow-xl relative flex flex-col max-h-[90vh]">

        {{-- HEADER (No scroll) --}}
        <div class="p-6 border-b flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">
                    Detalles del Bien - {{ $bienSeleccionado->numero_inventario }}
                </h2>
                <p class="text-gray-600 text-sm">Información completa del bien</p>
            </div>

            <button wire:click="$set('modalDetalle', false)"
                    class="text-gray-500 hover:text-black">
                <svg xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor"
                    class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- CONTENIDO SCROLLEABLE --}}
        <div class="p-6 space-y-8 overflow-y-auto">

            {{-- ESTADO + DESCRIPCIÓN --}}
            <div class="p-5 bg-gray-50 rounded-lg border">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-semibold">{{ $bienSeleccionado->descripcion }}</h3>
                        <p class="text-sm text-gray-500">
                            {{ $bienSeleccionado->bien_uso ? 'Bien de Uso' : 'Bien de Consumo' }}
                        </p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold 
                        @if ($bienSeleccionado->estado=='asignado') bg-indigo-100 text-indigo-700
                        @elseif ($bienSeleccionado->estado=='stock') bg-blue-100 text-blue-700
                        @else bg-red-100 text-red-700 @endif">
                        {{ ucfirst($bienSeleccionado->estado) }}
                    </span>
                </div>
            </div>


            {{-- DATOS DEL REMITO --}}
            <div>
                <h3 class="text-lg font-bold mb-3">Datos del Remito</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                    <div><p class="font-semibold">Fecha Recepción</p>
                        <p>{{ $bienSeleccionado->remito->fecha_recepcion }}</p></div>

                    <div><p class="font-semibold">N° Remito</p>
                        <p>{{ $bienSeleccionado->remito->numero_remito }}</p></div>

                    <div><p class="font-semibold">Expediente</p>
                        <p>{{ $bienSeleccionado->remito->numero_expediente }}</p></div>

                    <div><p class="font-semibold">Orden Provisión</p>
                        <p>{{ $bienSeleccionado->remito->orden_provision }}</p></div>

                    <div><p class="font-semibold">Tipo Compra</p>
                        <p>{{ $bienSeleccionado->remito->tipo_compra }}</p></div>

                    <div><p class="font-semibold">Proveedor</p>
                        <p>{{ optional($bienSeleccionado->remito->proveedor)->razon_social }}</p></div>

                </div>

                @if ($bienSeleccionado->remito->foto_remito)
                <div class="mt-4">
                    <p class="font-semibold">Remito Digitalizado</p>
                    <img src="{{ asset('storage/'.$bienSeleccionado->remito->foto_remito) }}"
                        class="w-48 rounded border shadow">
                </div>
                @endif
            </div>



            {{-- DOCUMENTACIÓN --}}
            @if ($bienSeleccionado->documentacion)
            <div>
                <h3 class="text-lg font-bold mb-3">Documentación del Bien</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                    <div><p class="font-semibold">Acta</p>
                        <p>{{ $bienSeleccionado->documentacion->numero_acta }}</p></div>

                    <div><p class="font-semibold">Fecha Acta</p>
                        <p>{{ $bienSeleccionado->documentacion->fecha_acta }}</p></div>

                    <div><p class="font-semibold">Resolución</p>
                        <p>{{ $bienSeleccionado->documentacion->numero_resolucion }}</p></div>

                    <div><p class="font-semibold">Factura</p>
                        <p>{{ $bienSeleccionado->documentacion->numero_factura }}</p></div>

                    <div><p class="font-semibold">Fecha Factura</p>
                        <p>{{ $bienSeleccionado->documentacion->fecha_factura }}</p></div>

                    <div><p class="font-semibold">Monto</p>
                        <p>$ {{ number_format($bienSeleccionado->documentacion->monto,2,',','.') }}</p></div>

                    <div><p class="font-semibold">Partida Presup.</p>
                        <p>{{ $bienSeleccionado->documentacion->partida_presupuestaria }}</p></div>

                    <div><p class="font-semibold">Orden Pago</p>
                        <p>{{ $bienSeleccionado->documentacion->orden_pago }}</p></div>

                    <div class="md:col-span-2">
                        <p class="font-semibold">Observaciones</p>
                        <p>{{ $bienSeleccionado->documentacion->observaciones }}</p>
                    </div>

                </div>
            </div>
            @endif



            {{-- UBICACIÓN ACTUAL --}}
            @php
                $ultimaAsignacion = $bienSeleccionado->asignaciones->sortByDesc('fecha_asignacion')->first();
            @endphp

            @if($ultimaAsignacion)
            <div>
                <h3 class="text-lg font-bold mb-3">Ubicación Actual</h3>

                <div class="p-4 bg-gray-50 border rounded-lg">
                    <p class="font-bold">{{ optional($ultimaAsignacion->dependencia)->nombre }}</p>
                    <p class="text-sm text-gray-600">Código: {{ optional($ultimaAsignacion->dependencia)->codigo }}</p>
                    <p class="text-sm text-gray-600">Ubicación: {{ optional($ultimaAsignacion->dependencia)->ubicacion }}</p>
                    <p class="text-sm text-gray-600">Asignado el: {{ $ultimaAsignacion->fecha_asignacion }}</p>
                </div>
            </div>
            @endif



            {{-- HISTÓRICO --}}
            <div>
                <h3 class="text-lg font-bold mb-3">Histórico de Movimientos</h3>

                @php
                    $historial = collect([]);

                    if ($bienSeleccionado->remito) {
                        $historial->push([
                            'fecha' => $bienSeleccionado->remito->fecha_recepcion,
                            'tipo' => 'Recepción',
                            'descripcion' =>
                                'Recepcionado. Proveedor: ' .
                                optional($bienSeleccionado->remito->proveedor)->razon_social .
                                ', Remito: ' . $bienSeleccionado->remito->numero_remito,
                        ]);
                    }

                    if ($bienSeleccionado->documentacion) {
                        $historial->push([
                            'fecha' => $bienSeleccionado->documentacion->fecha_acta,
                            'tipo' => 'Documentación',
                            'descripcion' =>
                                'Acta: ' . $bienSeleccionado->documentacion->numero_acta .
                                ', Resolución: ' . $bienSeleccionado->documentacion->numero_resolucion,
                        ]);
                    }

                    foreach ($bienSeleccionado->asignaciones as $asig) {
                        $historial->push([
                            'fecha' => $asig->fecha_asignacion,
                            'tipo' => 'Asignación',
                            'descripcion' =>
                                'Asignado a ' . optional($asig->dependencia)->nombre .
                                ' — ' . ($asig->observacion ?? 'Sin observaciones'),
                        ]);
                    }

                    $historial = $historial->sortByDesc('fecha');
                @endphp

                <div class="space-y-3">
                    @foreach ($historial as $item)
                        <div class="p-4 bg-gray-50 border rounded-lg flex justify-between items-center">
                            <div>
                                <p class="font-semibold">{{ $item['fecha'] }}</p>
                                <p class="text-gray-700">{{ $item['descripcion'] }}</p>
                            </div>
                            <span class="px-3 py-1 bg-gray-200 rounded-full text-xs font-medium">
                                {{ $item['tipo'] }}
                            </span>
                        </div>
                    @endforeach
                </div>

            </div>

        </div>

    </div>
</div>
@endif
