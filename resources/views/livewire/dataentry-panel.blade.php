{{-- resources/views/livewire/dataentry-panel.blade.php --}}
<div class="space-y-8">

    <div class="space-y-8">
    <!-- Encabezado -->
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Panel de Cargador</h1>
            <p class="text-gray-600">Completar documentación de bienes</p>
        </div>

        <div class="flex gap-2">
            <button wire:click="mostrarModal('sin-asignar')" 
                class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Bienes Sin Asignar
            </button>

            <button wire:click="mostrarModal('exportar')" 
                class="flex items-center gap-2 px-4 py-2 bg-indigo-900 text-white rounded-lg hover:bg-indigo-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Exportar a Excel
            </button>
        </div>
    </div>

    

    @if (session()->has('message'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between">
        <span>{{ session('message') }}</span>
        <button onclick="this.parentElement.remove()" class="text-green-800 hover:text-green-900">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
@endif


    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-red-800 hover:text-red-900">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    @endif

    <!-- Buscar bien -->
    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
        <h3 class="font-semibold flex items-center gap-2 mb-4 text-gray-800">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Buscar Bien
        </h3>

        <div>
            <label class="text-sm font-medium text-gray-700">
    Número de Remito u Orden de Provisión
</label>

<input type="text" 
    wire:model.defer="busqueda" 
    placeholder="Ej: REM-1234 o OP-5849/25"
    class="w-full mt-1 px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

        </div>

        <button wire:click="buscar" 
            class="w-full mt-4 bg-indigo-900 text-white rounded-lg py-2 hover:bg-indigo-800 transition-colors font-medium">
            Buscar
        </button>
    </div>

    <!-- Pendientes -->
<div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
    <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Pendientes de Documentación
    </h3>
    <p class="text-sm text-gray-500 mb-4">Bienes que requieren completar documentación</p>

    <div class="space-y-2 max-h-96 overflow-y-auto">
        @forelse ($this->pendientes as $key => $grupo)
            @if($grupo['cantidad'] > 1)
                {{-- Grupo con múltiples bienes --}}
                <div class="border rounded-lg overflow-hidden @if($grupoSeleccionado === $key) ring-2 ring-indigo-500 @endif">
                    {{-- Encabezado del grupo - CLICKEABLE --}}
                    <div wire:click="seleccionarGrupo('{{ $key }}')"
                         class="p-3 bg-gradient-to-r from-indigo-50 to-blue-50 border-b cursor-pointer hover:from-indigo-100 hover:to-blue-100 transition-colors @if($grupoSeleccionado === $key) from-indigo-100 to-blue-100 @endif"
                         x-data="{ open: false }">
                        <div class="flex justify-between items-center">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    <p class="font-semibold text-gray-900">Grupo: {{ $grupo['cantidad'] }} bienes</p>
                                </div>
                                <div class="flex gap-2 mt-2 flex-wrap">
                                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded">
                                        📋 Remito: {{ $grupo['numero_remito'] }}
                                    </span>
                                    <span class="text-xs px-2 py-1 bg-indigo-100 text-indigo-700 rounded">
                                        📁 Expediente: {{ $grupo['numero_expediente'] }}
                                    </span>
                                    <span class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded">
                                        🧾 O.P.: {{ $grupo['orden_provision'] }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($grupoSeleccionado === $key)
                                    <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded font-medium">
                                        Seleccionado
                                    </span>
                                @endif
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform"
                                     :class="{ 'rotate-90': open }"
                                     @click.stop="open = !open"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                        
                        {{-- Items del grupo (expandibles) --}}
                        <div x-show="open" 
                             x-transition
                             @click.stop
                             class="mt-3 space-y-2">
                            @foreach($grupo['items'] as $b)
                                <div class="p-2 bg-white border rounded">
                                    <p class="font-medium text-sm text-gray-900">{{ $b->numero_inventario }}</p>
                                    <p class="text-xs text-gray-600">{{ $b->descripcion }}</p>
                                    <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                        {{ $b->cuenta->codigo ?? 'N/A' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                {{-- Bien individual - CLICKEABLE --}}
@php $b = $grupo['items']->first(); @endphp
<div wire:click="seleccionarBien({{ $b->id }})"
     class="border rounded-lg bg-[#f8faff] hover:bg-indigo-50 cursor-pointer transition-colors p-4 mb-2">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="font-semibold text-gray-800">{{ $b->numero_inventario }}</p>
            <p class="text-gray-600 text-sm">{{ strtoupper($b->descripcion) }}</p>
            <span class="text-xs text-gray-400">{{ $b->cuenta->codigo ?? '' }}</span>
        </div>

        <div class="flex flex-wrap gap-2 mt-3 sm:mt-0">
            <span class="px-2 py-0.5 text-xs rounded bg-indigo-100 text-indigo-700 font-medium">
                Remito: {{ $b->remito->numero_remito ?? 'N/A' }}
            </span>
            <span class="px-2 py-0.5 text-xs rounded bg-yellow-100 text-yellow-700 font-medium">
                Expediente: {{ $b->remito->numero_expediente ?? 'N/A' }}
            </span>
            <span class="px-2 py-0.5 text-xs rounded bg-pink-100 text-pink-700 font-medium">
                O.P.: {{ $b->remito->orden_provision ?? 'N/A' }}
            </span>
        </div>
    </div>

    <div class="mt-3 flex items-center gap-1 text-sm text-gray-600 border-t border-gray-100 pt-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 9v2m0 4v2m0-4h.01M12 5v.01M12 19v.01" />
        </svg>
        Bien individual – No se puede documentar en grupo
    </div>
</div>



                   
            @endif
        @empty
            <div class="text-center py-12 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p>No hay bienes pendientes de documentación.</p>
            </div>
        @endforelse
    </div>
</div>


    <!-- Documentación asociada para GRUPO -->
@if ($grupoSeleccionado)
<div id="form-documentacion">
    @php 
        $grupo = $this->pendientes->get($grupoSeleccionado);
    @endphp
    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
        <h3 class="font-semibold text-gray-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Documentación para {{ $grupo['cantidad'] }} Bienes
        </h3>

        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 p-4 rounded-lg mb-6 border border-indigo-100">
            <p class="font-semibold text-gray-900 text-lg mb-2">Grupo Seleccionado</p>
            <div class="flex gap-3 text-sm text-gray-700">
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded">
                    📋 Remito: {{ $grupo['numero_remito'] }}
                </span>
                <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded">
                    📁 Expediente: {{ $grupo['numero_expediente'] }}
                </span>
                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded">
                    🧾 O.P.: {{ $grupo['orden_provision'] }}
                </span>
            </div>
            <p class="text-sm text-gray-600 mt-2">
                La documentación se aplicará a los {{ $grupo['cantidad'] }} bienes de este grupo
            </p>
        </div>

        <form wire:submit.prevent="guardarDocumentacionGrupo">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Número de Acta -->
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Número de Acta
    </label>
    <input type="text" 
        wire:model="numero_acta" 
        placeholder="Ej: ACTA-2024-0000 o 5849/25"
        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        @error('numero_acta')
  <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
@enderror

</div>

                <!-- Fecha de Acta -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Fecha de Acta
                    </label>
                    <input type="date" 
                        wire:model="fecha_acta"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                
<!-- Número de Resolución -->
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Número de Resolución
    </label>
    <input type="text" 
        wire:model="numero_resolucion" 
        placeholder="Ej: RES-2024-0000 o 4892-42"
        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        @error('numero_resolucion')
  <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
@enderror

</div>

                <!-- Número de Factura -->
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Número de Factura
    </label>
    <input type="text" 
        wire:model="numero_factura" 
        placeholder="Ej: FAC-A-0000000 o 43295"
        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        @error('numero_factura')
  <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
@enderror

</div>

                <!-- Fecha de Factura -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Fecha de Factura
                    </label>
                    <input type="date" 
                        wire:model="fecha_factura"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Monto -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Monto
                    </label>
                    <input type="number" 
                        wire:model="monto" 
                        step="0.01"
                        placeholder="0.00"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Partida Presupuestaria -->
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Partida Presupuestaria
    </label>
    <input type="text" 
        wire:model="partida_presupuestaria" 
        placeholder="Ej: 2.9.3 o 4892-42"
        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        @error('partida_presupuestaria')
  <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
@enderror

</div>

                <!-- Orden de Pago -->
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Orden de Pago
    </label>
    <input type="text" 
        wire:model="orden_pago" 
        placeholder="Ej: PO-2024-0000 o 5849/25"
        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        @error('orden_pago')
  <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
@enderror

</div>

                <!-- Estado -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Estado
                    </label>
                    <select wire:model="estado"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="pendiente">Pendiente</option>
                        <option value="completo">Completo</option>
                        <option value="revisado">Revisado</option>
                    </select>
                </div>
            </div>

           <!-- Observaciones -->
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Observaciones
                </label>
                <textarea 
                    wire:model="observaciones"
                    rows="3"
                    placeholder="Observaciones adicionales..."
                    class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <div class="flex justify-end mt-6 gap-2">
                <button type="button" wire:click="$set('grupoSeleccionado', null)" 
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-900 text-white rounded-lg hover:bg-indigo-800 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Guardar para {{ count($bienesDelGrupo) }} Bienes
                </button>
            </div>
        </form>
    </div>
</div>
@endif

@if ($bienSeleccionado && !$grupoSeleccionado)
<div id="form-documentacion">
    @php 
        $bien = App\Models\Bien::with('remito')->find($bienSeleccionado);
    @endphp

    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
        <h3 class="font-semibold text-gray-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Documentación para el Bien {{ $bien->numero_inventario }}
        </h3>

        <form wire:submit.prevent="guardarDocumentacion">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                <!-- Número de Acta -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número de Acta</label>
                    <input type="text" wire:model="numero_acta" placeholder="Ej: ACTA-2025-0001"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Fecha de Acta -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Acta</label>
                    <input type="date" wire:model="fecha_acta"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Número de Resolución -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número de Resolución</label>
                    <input type="text" wire:model="numero_resolucion" placeholder="Ej: RES-2025-0045"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Número de Factura -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número de Factura</label>
                    <input type="text" wire:model="numero_factura" placeholder="Ej: FAC-A-0000001"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Fecha de Factura -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Factura</label>
                    <input type="date" wire:model="fecha_factura"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Monto -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Monto</label>
                    <input type="number" step="0.01" wire:model="monto"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Partida Presupuestaria -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Partida Presupuestaria</label>
                    <input type="text" wire:model="partida_presupuestaria" placeholder="Ej: 3.2.1 o 293-45"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Orden de Pago -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Orden de Pago</label>
                    <input type="text" wire:model="orden_pago" placeholder="Ej: OP-2025-0009"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Estado -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select wire:model="estado"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="pendiente">Pendiente</option>
                        <option value="completo">Completo</option>
                        <option value="revisado">Revisado</option>
                    </select>
                </div>
            </div>

            <!-- Observaciones -->
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                <textarea wire:model="observaciones" rows="3"
                    placeholder="Observaciones adicionales..."
                    class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <div class="flex justify-end mt-6 gap-2">
                <button type="button" wire:click="$set('bienSeleccionado', null)" 
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-900 text-white rounded-lg hover:bg-indigo-800 transition-colors flex items-center gap-2">
                    Guardar Documentación
                </button>
            </div>
        </form>
    </div>
</div>
@endif



   <!-- Modal Exportar a Excel -->
<div x-data="{ open: @entangle('showExportModal') }" x-show="open"
    class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">

    <div @click.away="open = false"
        class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Exportar a Excel por Fechas</h2>

        <form wire:submit.prevent="exportarExcelPorFechas">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-700">Fecha Inicial</label>
                    <input type="date" wire:model="fechaInicio" required
                        class="border rounded w-full p-2">
                    @error('fechaInicio') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Fecha Final</label>
                    <input type="date" wire:model="fechaFin" required
                        class="border rounded w-full p-2">
                    @error('fechaFin') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end mt-6 space-x-3">
                <button type="button" @click="open = false"
                    class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-700 text-white rounded hover:bg-indigo-800 flex items-center gap-2">
                    <i class="bi bi-download"></i> Exportar
                </button>
                <button type="button" 
    wire:click="exportarTodo"
    class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800">
    Exportar Todo
</button>

            </div>
        </form>
    </div>
</div>

    <!-- Modal Bienes Completados -->
@if($modalSinAsignar)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" wire:click="cerrarModal('sin-asignar')">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 p-6 max-h-[80vh] overflow-y-auto" wire:click.stop>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Bienes Completados</h3>
                <button wire:click="cerrarModal('sin-asignar')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="space-y-3">
                @forelse($this->bienesSinAsignar as $key => $grupo)
                    @if($grupo['cantidad'] > 1)
                        {{-- Grupo con múltiples bienes --}}
                        <div class="border rounded-lg overflow-hidden">
                            {{-- Encabezado del grupo --}}
                            <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b"
                                 x-data="{ open: false }">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <p class="font-semibold text-gray-900">Grupo: {{ $grupo['cantidad'] }} bienes completados</p>
                                        </div>
                                        <div class="flex gap-2 flex-wrap">
                                            <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded">
                                                📋 Remito: {{ $grupo['numero_remito'] }}
                                            </span>
                                            <span class="text-xs px-2 py-1 bg-indigo-100 text-indigo-700 rounded">
                                                📁 Expediente: {{ $grupo['numero_expediente'] }}
                                            </span>
                                            <span class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded">
                                                🧾 O.P.: {{ $grupo['orden_provision'] }}
                                            </span>
                                        </div>
                                    </div>
                                    <button @click="open = !open" 
                                            class="ml-4 text-gray-500 hover:text-gray-700">
                                        <svg class="w-5 h-5 transform transition-transform"
                                             :class="{ 'rotate-90': open }"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                                
                                {{-- Items del grupo (expandibles) --}}
                                <div x-show="open" 
                                     x-transition
                                     class="mt-3 space-y-2">
                                    @foreach($grupo['items'] as $bien)
                                        <div class="p-3 bg-white border rounded-lg flex justify-between items-center hover:bg-gray-50">
                                            <div>
                                                <p class="font-semibold text-sm">{{ $bien->numero_inventario }}</p>
                                                <p class="text-xs text-gray-600">{{ $bien->descripcion }}</p>
                                                <div class="flex gap-1 mt-1">
                                                    <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                                        {{ $bien->cuenta->codigo ?? 'N/A' }}
                                                    </span>
                                                    <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded">
                                                        Completo
                                                    </span>
                                                </div>
                                            </div>
                                            <button wire:click="verDetalles({{ $bien->id }})" 
                                                class="text-sm px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                                                Ver Detalles
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Bien individual --}}
                        @php $bien = $grupo['items']->first(); @endphp
                        <div class="p-3 border rounded-lg hover:bg-gray-50 flex justify-between items-center">
                            <div>
                                <p class="font-semibold">{{ $bien->numero_inventario }}</p>
                                <p class="text-sm text-gray-600">{{ $bien->descripcion }}</p>
                                <div class="flex gap-1 mt-1">
                                    <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                        {{ $bien->cuenta->codigo ?? 'N/A' }}
                                    </span>
                                    <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded">
                                        Completo
                                    </span>
                                    @if($bien->remito)
                                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded">
                                        Remito: {{ $bien->remito->numero_remito }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <button wire:click="verDetalles({{ $bien->id }})" 
                                class="text-sm px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                                Ver Detalles
                            </button>
                        </div>
                    @endif
                @empty
                    <div class="text-center py-12 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>No hay bienes completados</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6 flex justify-end">
                <button wire:click="cerrarModal('sin-asignar')" 
                    class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
@endif

<!-- Modal Detalles del Bien -->
@if($modalDetalles && $bienDetalle)
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    wire:click="cerrarModal('detalles')">

    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 p-6 overflow-y-auto max-h-[90vh]" 
        wire:click.stop>

        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
                <x-heroicon-o-information-circle class="w-6 h-6 text-indigo-600" />
                Detalles del Bien
            </h3>
            <button wire:click="cerrarModal('detalles')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

       <!-- 📸 Foto -->
<div class="flex justify-center mb-6">
    @if($bienDetalle->remito && $bienDetalle->remito->foto_remito)
        <img src="{{ asset('storage/' . $bienDetalle->remito->foto_remito) }}" 
             alt="Foto del remito {{ $bienDetalle->remito->numero_remito }}"
             class="max-h-64 rounded-lg shadow-md border object-contain hover:scale-105 transition-transform duration-300"
             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'text-center text-red-500\'><svg class=\'w-10 h-10 mx-auto mb-2\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z\'/></svg><p class=\'text-sm\'>Error: Imagen no encontrada</p><p class=\'text-xs text-gray-500 mt-1\'>{{ $bienDetalle->remito->foto_remito }}</p></div>';">
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
        <!-- 🧾 SECCIÓN REMITO (incluye bien y cuenta) -->
        <h4 class="text-lg font-semibold text-indigo-700 border-b border-indigo-200 pb-1 mb-3">
            Remito
        </h4>
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

        <!-- 📚 SECCIÓN DOCUMENTACIÓN ASOCIADA -->
        <h4 class="text-lg font-semibold text-indigo-700 border-b border-indigo-200 pb-1 mb-3">
            Documentación Asociada
        </h4>
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

        <!-- Botón cerrar -->
        <div class="mt-6 flex justify-end">
            <button wire:click="cerrarModal('detalles')" 
                class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                Cerrar
            </button>
        </div>
    </div>
</div>
@endif

<script>
    window.addEventListener('descargar-excel', event => {
        const inicio = event.detail.inicio;
        const fin = event.detail.fin;
        const url = `/exportar-excel/${inicio}/${fin}`;
        window.open(url, '_blank'); // abre el archivo Excel en otra pestaña
    });
</script>

<script>
    window.addEventListener('scroll-a-formulario', () => {
        const form = document.getElementById('form-documentacion');
        if (form) {
            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
</script>


</div>