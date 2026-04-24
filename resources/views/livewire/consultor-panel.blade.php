{{-- resources/views/livewire/consultor-panel.blade.php --}}
<div x-data="{}" class="space-y-6">


    <!-- Encabezado -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Panel de Consultor</h1>
            <p class="text-gray-600">Consulta de bienes patrimoniales</p>
        </div>

        @if($vistaActual === 'detalle')
        <button wire:click="volverATarjetas"
            class="flex items-center gap-2 px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-800 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver
        </button>
        @endif
    </div>

    <!-- Mensajes -->
    @if (session()->has('message'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between">
            <span>{{ session('message') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-800 hover:text-green-900">×</button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-red-800 hover:text-red-900">×</button>
        </div>
    @endif

    <!-- Resumen -->
    <div class="flex flex-wrap gap-3">
        <div class="bg-indigo-50 border border-indigo-100 px-4 py-2 rounded-lg text-indigo-800 text-sm">
            Total de bienes: <span class="font-semibold">{{ \App\Models\Bien::count() }}</span>
        </div>
        <div class="bg-green-50 border border-green-100 px-4 py-2 rounded-lg text-green-800 text-sm">
            Con QR: <span class="font-semibold">{{ $qrCount ?? \App\Models\Bien::whereNotNull('codigo_qr')->count() }}</span>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex gap-2 border-b border-gray-200">
        <button wire:click="$set('vistaActual', 'tarjetas')"
            class="flex items-center gap-2 px-4 py-2 border-b-2 transition-colors 
            @if($vistaActual === 'tarjetas') border-indigo-600 text-indigo-600 
            @else border-transparent text-gray-600 hover:text-gray-900 @endif">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M4 6h4v4H4V6zm0 8h4v4H4v-4zm8-8h4v4h-4V6zm0 8h4v4h-4v-4zm8-8h4v4h-4V6zm0 8h4v4h-4v-4z"/>
            </svg>
            Consulta de Bienes
        </button>
    </div>

    <!-- Vista principal -->
    @if($vistaActual === 'tarjetas')
        {{-- === LISTADO DE BIENES === --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
  <!-- Panel izquierdo -->
<div class="lg:col-span-3 space-y-4">
    <!-- Búsqueda -->
    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold flex items-center gap-2 mb-3 text-gray-800">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Buscar Bienes
        </h3>

        <p class="text-xs text-gray-600 mb-3">
            Buscar por inventario, descripción o cuenta contable
        </p>

        <!-- 🔍 Input + botón -->
        <div class="flex">
            <input type="text" 
                wire:model="busqueda" 
                placeholder="Ingrese término de búsqueda"
                class="flex-grow px-3 py-2 text-sm border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

            <button wire:click="buscar" 
                class="px-4 py-2 bg-indigo-900 text-white text-sm font-semibold rounded-r-lg hover:bg-indigo-800 transition">
                Buscar
            </button>
        </div>

        <!-- 🧩 Escaneo QR -->
        <button wire:click="simularEscaneoQR" 
            class="w-full mt-3 border border-gray-300 text-gray-700 rounded-lg py-2 text-sm hover:bg-gray-50 transition flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
            </svg>
            Simular Escaneo QR
        </button>
    </div>

    <!-- Filtros -->
    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-800 mb-3 text-sm">Filtros</h3>

        <!-- Filtro Estado -->
        <div class="mb-3">
            <label class="text-xs font-medium text-gray-700 mb-1 block">Estado</label>
            <select wire:model="filtroEstado"
                class="w-full px-3 py-2 text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                <option value="">Todos</option>
                <option value="stock">En Stock</option>
                <option value="asignado">Asignado</option>
                <option value="baja">De Baja</option>
            </select>
        </div>

        <!-- Filtro Cuenta -->
        <div class="mb-3">
            <label class="text-xs font-medium text-gray-700 mb-1 block">Cuenta Contable</label>
            <select wire:model="filtroCuenta"
                class="w-full px-3 py-2 text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                <option value="">Todas</option>
                @foreach($cuentas as $cuenta)
                    <option value="{{ $cuenta->id }}">{{ $cuenta->codigo }}</option>
                @endforeach
            </select>
        </div>

        <button wire:click="limpiarFiltros"
            class="w-full text-xs text-gray-600 hover:text-gray-900 underline">
            Limpiar filtros
        </button>
    </div>
</div>
<!-- 👆 IMPORTANTE: este div cierra antes de lg:col-span-9 -->

               
            <!-- Panel derecho: tarjetas -->
            <div class="lg:col-span-9">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($this->bienes as $bien)
                        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-md hover:shadow-lg transition-shadow">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-lg font-bold text-gray-900">{{ $bien->numero_inventario }}</h3>
                                @if($bien->codigo_qr)
                                <button wire:click="mostrarQR({{ $bien->id }})" 
                                    class="text-indigo-600 hover:text-indigo-800" title="Ver código QR">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                    </svg>
                                </button>
                                @endif
                            </div>
@if($bien->foto)
    <div class="flex justify-center my-4">
        <img src="{{ asset('storage/' . $bien->foto) }}" 
             alt="Foto del bien"
             class="w-32 h-32 object-cover rounded border border-gray-200 shadow-sm hover:scale-105 transition-transform cursor-pointer"
             title="Foto del bien">
    </div>
@else
    <div class="flex justify-center my-4">
        <div class="w-32 h-32 bg-gray-100 rounded flex items-center justify-center text-gray-400 text-xs">
            Sin foto
        </div>
    </div>
@endif

                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $bien->descripcion }}</p>

                            @if($bien->codigo_qr)
                                <div class="flex justify-center my-4">
                                    <img src="{{ asset('storage/' . $bien->codigo_qr) }}" class="w-32 h-32 object-contain">
                                </div>
                            @else
                                <div class="flex justify-center my-4">
                                    <div class="w-32 h-32 bg-gray-100 rounded flex items-center justify-center text-gray-400 text-xs">
                                        Sin QR
                                    </div>
                                </div>
                            @endif

                            <div class="space-y-2 text-xs">
                                <div>
                                    <span class="font-semibold text-gray-700">Cuenta Contable</span>
                                    <p class="text-gray-900">{{ $bien->cuenta->codigo ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-700">Ubicación</span>
                                    <p class="text-gray-900">
                                        @if($bien->dependencia)
                                            {{ $bien->dependencia->nombre }}
                                            <br><span class="text-gray-500 text-xs">{{ $bien->dependencia->ubicacion }}</span>
                                        @else
                                            <span class="text-gray-500">Sin asignar</span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-700">Estado</span>
                                    <p>
                                        @if($bien->estado === 'stock')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                En Stock
                                            </span>
                                        @elseif($bien->estado === 'asignado')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Asignado
                                            </span>
                                        @elseif($bien->estado === 'baja')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                De Baja
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <button wire:click="verDetalle({{ $bien->id }})"
                                class="w-full mt-4 flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Ver Detalles
                            </button>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-12 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            No hay bienes para mostrar
                        </div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $this->bienes->links() }}
                </div>
            </div>
        </div>

    @else
        {{-- === DETALLE DEL BIEN === --}}
        @if($this->bienDetalle)
            @php $bien = $this->bienDetalle; @endphp
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-start mb-6 pb-4 border-b border-gray-100">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Información del Bien</h2>
                        <p class="text-sm text-gray-600 mt-1">Modo solo lectura</p>
                    </div>
                    @if($bien->codigo_qr)
                    <button wire:click="mostrarQR({{ $bien->id }})" 
                        class="flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                        Ver QR
                    </button>
                    @endif
                </div>

                <div class="bg-gradient-to-r from-indigo-50 to-blue-50 p-4 rounded-lg mb-6 border border-indigo-100">
                    <h3 class="text-2xl font-bold text-gray-900">{{ $bien->numero_inventario }}</h3>
                    <p class="text-gray-700 mt-2">{{ $bien->descripcion }}</p>
                    @if($bien->foto)
    <div class="mt-4 flex justify-center">
        <img src="{{ asset('storage/' . $bien->foto) }}" 
             alt="Foto del bien"
             class="max-w-sm rounded-lg shadow border border-gray-200">
    </div>
@else
    <p class="text-center text-gray-400 mt-3 italic">📷 Sin foto disponible</p>
@endif

                    <div class="flex gap-2 mt-3">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            @if($bien->bien_uso) bg-blue-100 text-blue-800 @else bg-purple-100 text-purple-800 @endif">
                            {{ $bien->bien_uso ? 'Bien de Uso' : 'Bien de Consumo' }}
                        </span>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                            @if($bien->estado === 'stock') bg-yellow-100 text-yellow-800
                            @elseif($bien->estado === 'asignado') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($bien->estado) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Datos de Recepción</h4>
                        <p><strong>N° Remito:</strong> {{ $bien->remito->numero_remito ?? 'N/A' }}</p>
                        <p><strong>N° Expediente:</strong> {{ $bien->remito->numero_expediente ?? 'N/A' }}</p>
                        <p><strong>Orden de Provisión:</strong> {{ $bien->remito->orden_provision ?? 'N/A' }}</p>
                        <p><strong>Fecha:</strong> 
                            {{ $bien->remito->fecha_recepcion ? \Carbon\Carbon::parse($bien->remito->fecha_recepcion)->format('d/m/Y') : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Documentación</h4>
                        @if($bien->documentacion)
                        <p><strong>Factura:</strong> {{ $bien->documentacion->numero_factura ?? 'N/A' }}</p>
                        <p><strong>Acta:</strong> {{ $bien->documentacion->numero_acta ?? 'N/A' }}</p>
                        <p><strong>Resolución:</strong> {{ $bien->documentacion->numero_resolucion ?? 'N/A' }}</p>
                        <p><strong>Monto:</strong> 
                            ${{ $bien->documentacion->monto ? number_format($bien->documentacion->monto, 2, ',', '.') : 'N/A' }}
                        </p>
                        @else
                        <p class="text-gray-500 text-sm">Sin documentación asociada.</p>
                        @endif
                    </div>
                </div>

                @if($bien->dependencia)
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-blue-900 mb-1">Ubicación Actual</h3>
                    <p class="text-blue-800 font-medium">{{ $bien->dependencia->nombre }}</p>
                    <p class="text-blue-700 text-sm">{{ $bien->dependencia->ubicacion ?? '' }}</p>
                </div>
                @endif
            </div>
        @endif
    @endif

    <!-- Modal QR -->
    @if($mostrarModalQR)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" wire:click="cerrarModalQR">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6" wire:click.stop>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Código QR</h3>
                    <button wire:click="cerrarModalQR" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="flex justify-center">
                    <img src="{{ $qrActual }}" alt="Código QR" class="max-w-full max-h-96">
                </div>
                <div class="mt-4 flex justify-end">
                    <button wire:click="cerrarModalQR" 
                        class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
