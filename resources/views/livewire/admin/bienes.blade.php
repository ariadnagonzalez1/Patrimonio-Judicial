<div class="space-y-6">
    <!-- HEADER -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Gestión de Bienes Patrimoniales</h2>
            <p class="text-sm text-gray-500">Administra los bienes registrados en el sistema</p>
        </div>
        <!-- Botón "Nuevo" -->
        <button wire:click="abrirModalCrear" 
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span>Nuevo Bien</span>
        </button>
    </div>

    <!-- FILTROS -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex-1 relative">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" wire:model.live="search" placeholder="Buscar por número o descripción..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
        </div>
        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
            <input type="checkbox" wire:model.live="mostrarInactivos" 
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition duration-200">
            Mostrar dados de baja
        </label>
    </div>

    <!-- MENSAJE -->
    @if (session('message'))
        <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg p-3 flex items-center space-x-2">
            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <!-- TABLA -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr class="text-gray-600 text-xs uppercase tracking-wider">
                    <th class="py-3 px-4 text-left">N° Inventario</th>
                    <th class="py-3 px-4 text-left">Descripción</th>
                    <th class="py-3 px-4 text-left">Dependencia</th>
                    <th class="py-3 px-4 text-center">Estado</th>
                    <th class="py-3 px-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($bienes as $b)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-4 font-medium text-gray-900">{{ $b->numero_inventario }}</td>
                        <td class="py-3 px-4 text-gray-700">{{ $b->descripcion }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $b->dependencia->nombre ?? '-' }}</td>
                        <td class="py-3 px-4 text-center">
                            @php
                                $color = match($b->estado) {
                                    'stock' => 'bg-green-100 text-green-700',
                                    'asignado' => 'bg-indigo-100 text-indigo-700',
                                    'baja' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                {{ ucfirst($b->estado) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <!-- Botones de acciones -->
                            <div class="flex justify-center space-x-2">
                                <!-- Editar -->
                                <button wire:click="editar({{ $b->id }})"
                                    class="text-yellow-600 hover:text-yellow-800 transition duration-200 p-1 rounded"
                                    title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                <!-- Dar de Baja -->
                                @if ($b->estado !== 'baja')
                                    <button wire:click="darDeBajaBien({{ $b->id }})"
                                        class="text-red-600 hover:text-red-800 transition duration-200 p-1 rounded"
                                        title="Dar de baja">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                @else
                                    <!-- Reactivar -->
                                    <button wire:click="activarBien({{ $b->id }})"
                                        class="text-green-600 hover:text-green-800 transition duration-200 p-1 rounded"
                                        title="Reactivar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-gray-500">No hay bienes registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINACIÓN -->
    @if ($bienes->hasPages())
        <div class="bg-white border border-gray-200 rounded-xl px-6 py-4 shadow-sm">
            {{ $bienes->links() }}
        </div>
    @endif

    <!-- MODAL CREAR/EDITAR -->
    @if ($showModal)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $modoEdicion ? 'Editar Bien' : 'Nuevo Bien' }}
                        </h3>
                        <!-- Botón cerrar modal -->
                        <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-600 transition duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Formulario -->
            
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <!-- Número de Inventario -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Número de Inventario</label>
        <input type="text" wire:model="numero_inventario"
            class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-200"
            readonly>
        @error('numero_inventario') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <!-- Descripción -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
        <input type="text" wire:model="descripcion"
            class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-200">
        @error('descripcion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <!-- Cantidad -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad</label>
        <input type="number" wire:model="cantidad" min="1"
            class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-200">
        @error('cantidad') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <!-- Precio Unitario -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Precio Unitario</label>
        <input type="number" wire:model="precio_unitario" step="0.01" min="0"
            class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-200">
        @error('precio_unitario') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <!-- Dependencia -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Dependencia</label>
        <select wire:model="dependencia_id"
            class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-200">
            <option value="">Seleccione una dependencia</option>
            @foreach ($dependencias as $d)
                <option value="{{ $d->id }}">{{ $d->nombre }}</option>
            @endforeach
        </select>
        @error('dependencia_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <!-- Proveedor -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Proveedor</label>
        <select wire:model="proveedor_id"
            class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-200">
            <option value="">Seleccione un proveedor</option>
            @foreach ($proveedores as $p)
                <option value="{{ $p->id }}">{{ $p->razon_social }}</option>
            @endforeach
        </select>
        @error('proveedor_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <!-- Cuenta -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Cuenta</label>
        <select wire:model="cuenta_id"
            class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-200">
            <option value="">Seleccione una cuenta</option>
            @foreach ($cuentas as $c)
                <option value="{{ $c->id }}">{{ $c->codigo }} – {{ $c->descripcion }}</option>
            @endforeach
        </select>
        @error('cuenta_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <!-- Remito -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Remito</label>
        <select wire:model="remito_id"
            class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-200">
            <option value="">Seleccione un remito</option>
            @foreach ($remitos as $r)
                <option value="{{ $r->id }}">{{ $r->numero_remito }}</option>
            @endforeach
        </select>
        @error('remito_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <!-- Observaciones (opcional) -->
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
        <textarea wire:model.defer="observaciones"
            class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-200"
            rows="2" placeholder="Detalles adicionales sobre el bien..."></textarea>
    </div>
</div>
                    <!-- Botones Modal -->
                    <div class="flex justify-end space-x-3 mt-6">
                        <button wire:click="cerrarModal"
                            class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition duration-200">
                            Cancelar
                        </button>
                        <button wire:click="{{ $modoEdicion ? 'actualizar' : 'guardar' }}"
                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition duration-200 flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>{{ $modoEdicion ? 'Actualizar' : 'Guardar' }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
</div>
