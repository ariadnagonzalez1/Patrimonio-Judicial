<div class="min-h-screen bg-gray-50 p-6">
    <!-- 🔹 Encabezado principal -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Sistema de Gestión Patrimonial</h1>
        <p class="text-gray-600">Poder Judicial</p>
    </div>

    <!-- 🔹 Encabezado del panel -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Panel del Gestor de Inventario</h1>
        <p class="text-gray-600 mt-1">Asignación y control de bienes patrimoniales</p>
    </div>

    <!-- 🔹 Mensajes de estado -->
    @if (session()->has('message'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex justify-between">
            <span>✅ {{ session('message') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700">✕</button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex justify-between">
            <span>❌ {{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-red-700">✕</button>
        </div>
    @endif

    <!-- 🔹 Vista de asignaciones -->
    @if($mostrarAsignaciones)
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    </svg>
                    Asignaciones Recientes
                </h2>
                <button wire:click="volverAlFormulario" 
                    class="flex items-center gap-2 px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-800">
                    ← Volver
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 text-left font-semibold text-gray-700">Cuenta</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-700">Inventario</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-700">Descripción</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-700">Dependencia</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-700">Fecha</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-700">Foto</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-700">QR</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($asignaciones as $asig)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-3 py-2">{{ $asig->bien->cuenta->codigo ?? '—' }}</td>
                                <td class="px-3 py-2">{{ $asig->bien->numero_inventario }}</td>
                                <td class="px-3 py-2">{{ $asig->bien->descripcion }}</td>
                                <td class="px-3 py-2">{{ $asig->dependencia->nombre }}</td>
                                <td class="px-3 py-2">{{ \Carbon\Carbon::parse($asig->fecha_asignacion)->format('d/m/Y') }}</td>
                                <td class="px-3 py-2">
                                    @if ($asig->bien->foto)
                                        <img src="{{ asset('storage/' . $asig->bien->foto) }}"
                                             wire:click="verFotoBien({{ $asig->bien->id }})"
                                             class="w-12 h-12 rounded object-cover border border-gray-300 shadow-sm hover:scale-105 transition cursor-pointer"
                                             title="Clic para ampliar">
                                    @else
                                        <span class="text-gray-400 italic">Sin foto</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    @if ($asig->bien->codigo_qr)
                                        <img src="{{ asset('storage/' . $asig->bien->codigo_qr) }}"
                                             alt="QR"
                                             class="w-12 h-12 object-contain border rounded shadow-sm"
                                             title="Código QR del Bien">
                                    @else
                                        <span class="text-gray-400 italic">Sin QR</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-gray-500">No hay asignaciones registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- 🔹 Panel principal: bienes y asignar -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            <!-- 🔸 Bienes en stock -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M20 7l-8-4-8 4v10l8 4 8-4V7z"/>
                    </svg>
                    Bienes en Stock
                </h2>

                <div class="space-y-2 max-h-[600px] overflow-y-auto">
                    @forelse($bienesStock as $bien)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox"
                                    wire:click="toggleBien({{ $bien->id }})"
                                    @checked(in_array($bien->id, $bienesSeleccionados))
                                    class="mt-1 w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-3">
                                            <span class="font-semibold text-gray-900">{{ $bien->numero_inventario }}</span>

                                            @if($bien->foto)
                                                <img src="{{ asset('storage/' . $bien->foto) }}" 
                                                     alt="Foto del bien"
                                                     wire:click="verFotoBien({{ $bien->id }})"
                                                     class="w-14 h-14 object-cover rounded border border-gray-200 shadow-sm hover:scale-105 transition-transform cursor-pointer"
                                                     title="Clic para ampliar">
                                            @else
                                                <div class="w-14 h-14 flex items-center justify-center border border-dashed border-gray-300 text-gray-400 text-[10px] rounded">
                                                    Sin foto
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <p class="text-sm text-gray-700 mb-2">{{ $bien->descripcion }}</p>
                                    <div class="text-xs space-y-1 mt-2">
                                        <div class="flex flex-wrap gap-2">
                                            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                                Expediente: {{ $bien->remito->numero_expediente ?? 'N/A' }}
                                            </span>
                                            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                                Orden: {{ $bien->remito->orden_provision ?? 'N/A' }}
                                            </span>
                                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded font-medium">
                                                {{ $bien->bien_uso ? 'Bien de Uso' : 'Bien de Consumo' }}
                                            </span>
                                        </div>

                                        <!-- 💰 Línea separada con número de cuenta -->
                                        <div class="flex items-center text-gray-700 mt-1">
                                            <svg class="w-4 h-4 text-indigo-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M12 8c-1.104 0-2 .672-2 1.5S10.896 11 12 11s2-.672 2-1.5S13.104 8 12 8zM4 7h16M4 12h16M4 17h16"/>
                                            </svg>
                                            <span class="text-xs font-medium">Número de cuenta:</span>
                                            <span class="ml-1 text-xs font-semibold text-gray-900">
                                                {{ $bien->cuenta->codigo ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-6">No hay bienes en stock</p>
                    @endforelse
                </div>
            </div>

            <!-- 🔸 Formulario de asignación -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Asignar a Dependencia</h2>
                <form wire:submit.prevent="asignarBienes" class="space-y-6">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Dependencia</label>
                        <select wire:model="dependencia_id"
                            class="w-full mt-1 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Seleccione</option>
                            @foreach($dependencias as $dep)
                                <option value="{{ $dep->id }}">{{ $dep->codigo }} - {{ $dep->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700">Fecha de Asignación</label>
                        <input type="date" wire:model="fecha_asignacion"
                            class="w-full mt-1 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700">Observación</label>
                        <textarea wire:model="observacion"
                            rows="3"
                            class="w-full mt-1 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Ej: Asignado al Juzgado N° 2..."></textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" wire:click="cancelar"
                            class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Asignar Bienes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="flex justify-end">
            <button wire:click="verAsignaciones"
                class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                📋 Ver Asignaciones
            </button>
        </div>
    @endif

    <!-- 🔹 Modal de foto -->
    @if($mostrarModalFoto)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" wire:click="cerrarModalFoto">
            <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 p-6" wire:click.stop>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Foto del Bien - {{ $bienSeleccionado->numero_inventario ?? 'N/A' }}
                    </h3>
                    <button wire:click="cerrarModalFoto" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>
                <div class="flex justify-center">
                    <img src="{{ $fotoUrl }}" alt="Foto del bien" class="max-w-full max-h-[70vh] rounded-lg shadow">
                </div>
            </div>
        </div>
    @endif
</div>