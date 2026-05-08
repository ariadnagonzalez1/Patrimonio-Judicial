{{-- resources/views/livewire/dataentry/_pendientes.blade.php --}}
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
                @php
                    $tieneDocumentacion = $grupo['items']->contains(fn($b) => $b->documentacion !== null);
                    $estadoGrupo = $grupo['items']->first()->documentacion->estado ?? null;
                @endphp

                <div class="border rounded-lg overflow-hidden @if($grupoSeleccionado === $key) ring-2 ring-indigo-500 @endif">
                    <div wire:click="seleccionarGrupo('{{ $key }}')"
                         class="p-3 bg-gradient-to-r from-indigo-50 to-blue-50 border-b cursor-pointer hover:from-indigo-100 hover:to-blue-100 transition-colors @if($grupoSeleccionado === $key) from-indigo-100 to-blue-100 @endif"
                         x-data="{ open: false }">

                        <div class="flex justify-between items-center">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    <p class="font-semibold text-gray-900">Grupo: {{ $grupo['cantidad'] }} bienes</p>

                                    {{-- Indicador de revisión --}}
                                    @if($tieneDocumentacion && $estadoGrupo !== 'completo')
                                        <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 bg-amber-100 text-amber-700 rounded-full font-medium">
                                            ✏️ En revisión
                                        </span>
                                    @endif
                                </div>

                                {{-- Badges siempre abajo --}}
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

                            <div class="flex items-center gap-2 ml-2">
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
                {{-- Bien individual --}}
                @php
                    $b = $grupo['items']->first();
                    $tieneDoc = $b->documentacion !== null;
                    $estadoDoc = $b->documentacion->estado ?? null;
                @endphp

                <div wire:click="seleccionarBien({{ $b->id }})"
                     class="border rounded-lg bg-[#f8faff] hover:bg-indigo-50 cursor-pointer transition-colors p-4">

                    {{-- Fila superior: inventario + descripción + indicador --}}
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $b->numero_inventario }}</p>
                            <p class="text-gray-600 text-sm">{{ strtoupper($b->descripcion) }}</p>
                            <span class="text-xs text-gray-400">{{ $b->cuenta->codigo ?? '' }}</span>
                        </div>

                        {{-- Indicador de revisión --}}
                        @if($tieneDoc && $estadoDoc !== 'completo')
                            <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 bg-amber-100 text-amber-700 rounded-full font-medium whitespace-nowrap">
                                ✏️ En revisión
                            </span>
                        @endif
                    </div>

                    {{-- Badges siempre abajo --}}
                    <div class="flex flex-wrap gap-2 mt-3">
                        <span class="px-2 py-0.5 text-xs rounded bg-indigo-100 text-indigo-700 font-medium">
                            📋 Remito: {{ $b->remito->numero_remito ?? 'N/A' }}
                        </span>
                        <span class="px-2 py-0.5 text-xs rounded bg-yellow-100 text-yellow-700 font-medium">
                            📁 Expediente: {{ $b->remito->numero_expediente ?? 'N/A' }}
                        </span>
                        <span class="px-2 py-0.5 text-xs rounded bg-pink-100 text-pink-700 font-medium">
                            🧾 O.P.: {{ $b->remito->orden_provision ?? 'N/A' }}
                        </span>
                    </div>

                    <div class="mt-3 flex items-center gap-1 text-sm text-gray-500 border-t border-gray-100 pt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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