{{-- resources/views/livewire/receptor/index.blade.php --}}
<div>
    <style>
        .hidden-message nav > div > div:first-child {
            display: none !important;
        }
    </style>

    {{-- Botón volver al dashboard — solo para administrador --}}
    @if(auth()->user()->rol->nombre === 'administrador')
        <div class="mb-4">
            <a href="{{ route('admin.panel') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <span>Volver</span>
            </a>
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Panel de Receptor</h1>
        <p class="text-gray-600 mt-1">Registro y alta de bienes patrimoniales</p>
    </div>

    {{-- Contenido Principal --}}
    <div class="bg-gray-50 rounded-lg p-6">

        @if($mostrarRegistros)
            {{-- ──────────────────────────────────────────
                 TABLA DE BIENES REGISTRADOS
            ────────────────────────────────────────── --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18m-9 5h9"/>
                        </svg>
                        Bienes Registrados
                    </h2>
                    <button wire:click="volverAlFormulario"
                            class="flex items-center gap-2 px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        <span>Volver</span>
                    </button>
                </div>

                <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
                    <table class="min-w-full text-sm divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold">#</th>
                                <th class="px-4 py-2 text-left font-semibold">Expediente</th>
                                <th class="px-4 py-2 text-left font-semibold">Orden de Provisión</th>
                                <th class="px-4 py-2 text-left font-semibold">N° Remito</th>
                                <th class="px-4 py-2 text-left font-semibold">Cuenta</th>
                                <th class="px-4 py-2 text-left font-semibold">N° Inventario</th>
                                <th class="px-4 py-2 text-left font-semibold">Descripción</th>
                                <th class="px-4 py-2 text-left font-semibold">Cantidad</th>
                                <th class="px-4 py-2 text-left font-semibold">Precio Unitario</th>
                                <th class="px-4 py-2 text-left font-semibold">Monto Total</th>
                                <th class="px-4 py-2 text-left font-semibold">Proveedor</th>
                                <th class="px-4 py-2 text-left font-semibold">Fecha Recepción</th>
                                <th class="px-4 py-2 text-left font-semibold">Foto del Remito</th>
                                <th class="px-4 py-2 text-center font-semibold">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($bienes as $index => $bien)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-center">{{ $bienes->firstItem() + $index }}</td>
                                    <td class="px-4 py-2">{{ $bien->remito->numero_expediente ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $bien->remito->orden_provision ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $bien->remito->numero_remito ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $bien->cuenta->codigo ?? '-' }}</td>
                                    <td class="px-4 py-2 font-mono">{{ $bien->numero_inventario ?? '-' }}</td>
                                    <td class="px-4 py-2 max-w-xs truncate" title="{{ $bien->descripcion ?? '' }}">
                                        {{ Str::limit($bien->descripcion ?? '-', 50) }}
                                    </td>
                                    <td class="px-4 py-2 text-center">{{ $bien->cantidad }}</td>
                                    <td class="px-4 py-2 text-right">${{ number_format($bien->precio_unitario, 2, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-right">${{ number_format($bien->monto_total, 2, ',', '.') }}</td>
                                    <td class="px-4 py-2">{{ Str::limit($bien->proveedor->razon_social ?? '-', 30) }}</td>
                                    <td class="px-4 py-2 text-center">
                                        @if($bien->remito && $bien->remito->fecha_recepcion)
                                            {{ \Carbon\Carbon::parse($bien->remito->fecha_recepcion)->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        @php
                                            $fotoPath   = $bien->foto_remito ?? null;
                                            $fotoExiste = $fotoPath && \Storage::disk('public')->exists($fotoPath);
                                        @endphp
                                        @if($fotoExiste)
                                            <a href="{{ asset('storage/' . $fotoPath) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $fotoPath) }}"
                                                     alt="Foto remito"
                                                     class="w-12 h-12 object-cover rounded-md border border-gray-300 shadow-sm hover:scale-105 transition-transform duration-200">
                                            </a>
                                        @elseif($fotoPath)
                                            <span class="text-red-500 text-xs" title="{{ $fotoPath }}">❌ No encontrada</span>
                                        @else
                                            <span class="text-gray-400 text-xs">Sin foto</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <div class="flex gap-2 justify-center">

                                            {{-- Editar --}}
                                            <button type="button"
                                                    wire:click="editarBien({{ $bien->id }})"
                                                    style="background-color: #f59e0b;"
                                                    onmouseover="this.style.backgroundColor='#d97706'"
                                                    onmouseout="this.style.backgroundColor='#f59e0b'"
                                                    class="px-3 py-1 text-white rounded-md text-xs transition flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Editar
                                            </button>

                                            {{-- Eliminar con SweetAlert2 — llama directo al método Livewire sin listener --}}
                                            <button type="button"
                                                    onclick="confirmarEliminacion({{ $bien->id }})"
                                                    class="px-3 py-1 bg-red-600 text-white rounded-md text-xs hover:bg-red-700 transition flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-9 0h10"/>
                                                </svg>
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Paginación --}}
                    <div class="mt-4 flex items-center gap-4 px-2 py-3">
                        <div class="hidden-message">
                            {{ $bienes->links() }}
                        </div>
                        <div class="text-sm text-gray-600">
                            Mostrando {{ $bienes->firstItem() }} a {{ $bienes->lastItem() }} de {{ $bienes->total() }} resultados
                        </div>
                    </div>
                </div>
            </div>

        @else
            {{-- ──────────────────────────────────────────
                 FORMULARIO DE REGISTRO
            ────────────────────────────────────────── --}}
            <form wire:submit.prevent="registrarBien" enctype="multipart/form-data">

                {{-- Botón Ver Registros --}}
                <div class="flex justify-end mb-4">
                    <button type="button" wire:click="verRegistros"
                        class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V7.414A2 2 0 0017.414 6L14 2.586A2 2 0 0012.586 2H4z"/>
                        </svg>
                        <span>Ver Registros</span>
                    </button>
                </div>

                {{-- Datos del Remito --}}
                @include('components.remito-data')

                {{-- Información del Bien --}}
                <div class="mb-6">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 shadow-sm space-y-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18m-9 5h9"/>
                                </svg>
                                <h3 class="text-base font-semibold text-gray-800">Información del Bien</h3>
                            </div>
                            <button type="button" wire:click="agregarFormulario"
                                class="flex items-center gap-1 px-3 py-1 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                <span>+ Agregar</span>
                            </button>
                        </div>

                        @foreach($formularios as $index => $form)
                            @include('components.form-item', ['form' => $form, 'index' => $index])
                        @endforeach
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex justify-end space-x-3">
                    <button type="button" wire:click="cancelar"
                        class="px-6 py-2 rounded-md bg-red-600 text-white font-medium hover:bg-red-700">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800 font-medium flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Registrar Bien</span>
                    </button>
                </div>
            </form>
        @endif
    </div>

    {{-- Modal de Edición --}}
    @if($editandoBien)
        @include('livewire.receptor.edit-modal')
    @endif

    {{-- Alertas --}}
    @include('livewire.partials.alerts')

    {{-- SweetAlert2 --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        // Confirmación de eliminación con SweetAlert2
        // Llama directo al método Livewire sin pasar por dispatch/listener
        function confirmarEliminacion(bienId) {
            Swal.fire({
                title:              '¿Estás seguro?',
                text:               '¡No podrás revertir esta acción!',
                icon:               'warning',
                showCancelButton:   true,
                confirmButtonColor: '#d33',
                cancelButtonColor:  '#6b7280',
                confirmButtonText:  'Sí, eliminar',
                cancelButtonText:   'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    // @this llama directo al componente Livewire sin listeners
                    @this.call('eliminarBien', bienId);
                }
            });
        }

        // Alertas de respuesta desde Livewire
        document.addEventListener('livewire:init', () => {
            Livewire.on('show-alert', (data) => {
                const items = (data.text || '').split('||').filter(Boolean);
                const icono = data.icon === 'success' ? '✅' : '❌';
                const html  = items.length > 1
                    ? '<ul style="text-align:left; margin-top:8px;">' +
                      items.map(i => `<li style="margin-bottom:6px;">${icono} ${i}</li>`).join('') +
                      '</ul>'
                    : items[0] || 'Operación completada';

                Swal.fire({
                    title:              data.title || 'Información',
                    html:               html,
                    icon:               data.icon || 'info',
                    confirmButtonText:  'Aceptar',
                    confirmButtonColor: '#3085d6',
                });
            });
        });
    </script>
</div>