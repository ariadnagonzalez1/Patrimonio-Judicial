{{-- resources/views/livewire/dataentry/_modal-completados.blade.php --}}
@if($modalSinAsignar)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        wire:click="cerrarModal('sin-asignar')">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 p-6 max-h-[80vh] overflow-y-auto"
            wire:click.stop>

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
                        <div class="border rounded-lg overflow-hidden">
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
                                    <button @click="open = !open" class="ml-4 text-gray-500 hover:text-gray-700">
                                        <svg class="w-5 h-5 transform transition-transform"
                                             :class="{ 'rotate-90': open }"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>

                                <div x-show="open" x-transition class="mt-3 space-y-2">
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