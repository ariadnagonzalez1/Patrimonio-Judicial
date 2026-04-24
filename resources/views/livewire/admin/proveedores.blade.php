<div class="space-y-6">
    <!-- HEADER MEJORADO -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Gestión de Proveedores</h2>
                <p class="mt-1 text-sm text-gray-500">Administra los proveedores del sistema patrimonial</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2 px-3 py-1.5 bg-blue-50 rounded-lg">
                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium text-blue-900">{{ $proveedores->total() }} registros</span>
                </div>
               <button wire:click="abrirModal"

                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span>Nuevo Proveedor</span>
                </button>
            </div>
        </div>
    </div>

    <!-- BARRA DE BÚSQUEDA Y FILTROS MEJORADA -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex-1">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" wire:model.live="search"
                           placeholder="Buscar por razón social, CUIL o email..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <label class="flex items-center space-x-2 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" wire:model.live="mostrarInactivos" 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition duration-200">
                    <span>Mostrar inactivos</span>
                </label>
                
                <div class="text-sm text-gray-500">
                    {{ $proveedores->total() }} resultado(s)
                </div>
            </div>
        </div>
    </div>

    <!-- MENSAJES DE ÉXITO -->
    @if (session('message'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center space-x-3">
            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="text-green-800 text-sm font-medium">{{ session('message') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-center space-x-3">
            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span class="text-red-800 text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- TABLA MEJORADA -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Razón Social</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CUIL</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="py-3 px-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="py-3 px-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($proveedores as $p)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="py-3 px-4 text-sm font-medium text-gray-900">{{ $p->razon_social }}</td>
                            <td class="py-3 px-4 text-sm text-gray-700">{{ $p->cuil }}</td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ $p->email ?? '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $p->estado 
                                        ? 'bg-green-100 text-green-800' 
                                        : 'bg-red-100 text-red-800' }}">
                                    {{ $p->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    <button wire:click="editar({{ $p->id }})"
                                        class="text-yellow-600 hover:text-yellow-800 transition duration-200 p-1 rounded"
                                        title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    
                                    @if ($p->estado)
                                        <button wire:click="darDeBajaProveedor({{ $p->id }})"
                                            class="text-red-600 hover:text-red-800 transition duration-200 p-1 rounded"
                                            title="Dar de baja">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    @else
                                        <button wire:click="activarProveedor({{ $p->id }})"
                                            class="text-green-600 hover:text-green-800 transition duration-200 p-1 rounded"
                                            title="Activar">
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
        <td colspan="6" class="py-8 px-4 text-center">
            <div class="flex flex-col items-center justify-center text-gray-500 py-6">
                <svg class="w-16 h-16 text-gray-300 mb-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-sm font-medium text-gray-900">No se encontraron los proveedores</p>
                <p class="text-xs text-gray-500 mt-1">Intenta ajustar los filtros de búsqueda</p>
            </div>
        </td>
    </tr>
@endempty

                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINACIÓN MEJORADA -->
    @if($proveedores->hasPages())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-6 py-4">
            {{ $proveedores->links() }}
        </div>
    @endif

    <!-- MODAL PARA AGREGAR/EDITAR PROVEEDOR -->
    @if ($showModal)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        {{ $modoEdicion ? 'Editar Proveedor' : 'Nuevo Proveedor' }}
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Razón Social</label>
                            <input type="text" wire:model="razon_social" placeholder="Razón social del proveedor"
                                class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            @error('razon_social') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CUIL</label>
                            <input type="text" wire:model="cuil" placeholder="CUIL del proveedor"
                                class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            @error('cuil') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                            <input type="email" wire:model="email" placeholder="Email del proveedor"
                                class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button wire:click="cerrarModal"
                            class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition duration-200">
                            Cancelar
                        </button>
                        <button wire:click="{{ $modoEdicion ? 'actualizarProveedor' : 'guardarProveedor' }}"
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
    @endif
</div>