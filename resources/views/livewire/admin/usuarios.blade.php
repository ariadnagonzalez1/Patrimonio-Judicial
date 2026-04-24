<div class="space-y-6">
    <!-- CABECERA -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Gestión de Usuarios del Sistema</h2>
            <p class="text-sm text-gray-500">Administra los usuarios y sus roles</p>
        </div>

        <!-- Botón Nuevo -->
        <button wire:click="$set('showModal', true)"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span>Nuevo Usuario</span>
        </button>
    </div>

    <!-- FILTROS -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex-1 relative">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" wire:model.debounce.500ms="search"
                placeholder="Buscar usuario por nombre o correo..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
        </div>
        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
            <input type="checkbox" wire:model="mostrarInactivos"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition duration-200">
            Mostrar inactivos
        </label>
    </div>

    <!-- MENSAJES -->
    @if (session('message'))
        <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg p-3 flex items-center space-x-2">
            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif
    
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 text-sm rounded-lg p-3 flex items-center space-x-2">
            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- TABLA -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr class="text-gray-600 text-xs uppercase tracking-wider">
                    <th class="py-3 px-4 text-left">Nombre</th>
                    <th class="py-3 px-4 text-left">Correo electrónico</th>
                    <th class="py-3 px-4 text-left">Rol</th>
                    <th class="py-3 px-4 text-center">Estado</th>
                    <th class="py-3 px-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($usuarios as $u)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-4 font-medium text-gray-900">{{ $u->nombre }}</td>
                        <td class="py-3 px-4 text-gray-700">{{ $u->email }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ ucfirst($u->rol->nombre ?? '-') }}</td>
                        <td class="py-3 px-4 text-center">
                            <span
                                class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $u->activo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $u->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center space-x-2">
                                <!-- 🛡️ BOTÓN EDITAR CON PROTECCIÓN -->
                                @php
                                    $puedeEditar = $u->id !== auth()->id();
                                @endphp
                                
                                @if($puedeEditar)
                                    <button wire:click="editar({{ $u->id }})"
                                        class="text-yellow-600 hover:text-yellow-800 transition duration-200 p-1 rounded"
                                        title="Editar usuario">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                @else
                                    <button disabled
                                        class="text-gray-400 cursor-not-allowed p-1 rounded opacity-50"
                                        title="No puedes editar tu propia cuenta desde aquí">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                @endif

                                <!-- 🛡️ BOTÓN DAR DE BAJA CON PROTECCIÓN -->
                                @if ($u->activo)
                                    @php
                                        $puedeDarDeBaja = $this->puedeDarseDeBaja($u->id);
                                    @endphp
                                    
                                    @if($puedeDarDeBaja)
                                        <button wire:click="confirmarBaja({{ $u->id }})"
                                            class="text-red-600 hover:text-red-800 transition duration-200 p-1 rounded"
                                            title="Dar de baja">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    @else
                                        <button disabled
                                            class="text-gray-400 cursor-not-allowed p-1 rounded opacity-50"
                                            title="No se puede dar de baja este usuario (es tu cuenta, el único admin, o el admin principal)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                        </button>
                                    @endif
                                @else
                                    {{-- Usuario inactivo: botón reactivar --}}
                                    <button wire:click="activar({{ $u->id }})"
                                        class="text-green-600 hover:text-green-800 transition duration-200 p-1 rounded"
                                        title="Reactivar usuario">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-gray-500">No se encontraron usuarios.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINACIÓN -->
    @if ($usuarios->hasPages())
        <div class="bg-white border border-gray-200 rounded-xl px-6 py-4 shadow-sm">
            {{ $usuarios->links() }}
        </div>
    @endif

    <!-- MODAL CREAR / EDITAR -->
    @if ($showModal)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md overflow-hidden">
                <div class="flex items-center justify-between border-b px-6 py-4 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $modoEdicion ? 'Editar Usuario' : 'Nuevo Usuario' }}
                    </h3>
                    <button wire:click="resetFormulario"
                        class="text-gray-400 hover:text-gray-600 transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" wire:model="nombre"
                            class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        @error('nombre') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                        <input type="email" wire:model="email"
                            class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                        <input type="password" wire:model="password"
                            class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                            placeholder="{{ $modoEdicion ? 'Dejar en blanco para mantener actual' : '' }}">
                        @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                        <select wire:model="role_id"
                            class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            <option value="">Seleccione un rol</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->id }}">{{ ucfirst($rol->nombre) }}</option>
                            @endforeach
                        </select>
                        @error('role_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Botones Modal -->
                    <div class="flex justify-end space-x-3 mt-6">
                        <button wire:click="resetFormulario"
                            class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition duration-200">
                            Cancelar
                        </button>
                        <button wire:click="{{ $modoEdicion ? 'actualizarUsuario' : 'guardarUsuario' }}"
                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition duration-200 flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ $modoEdicion ? 'Actualizar' : 'Guardar' }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL CONFIRMACIÓN -->
    @if ($showConfirm)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-sm p-6 text-center">
                <svg class="w-10 h-10 text-red-500 mx-auto mb-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M12 19a7 7 0 100-14 7 7 0 000 14z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">¿Dar de baja este usuario?</h3>
                <p class="text-sm text-gray-500 mb-5">Esta acción desactivará el acceso del usuario al sistema.</p>
                <div class="flex justify-center space-x-3">
                    <button wire:click="$set('showConfirm', false)"
                        class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition">Cancelar</button>
                    <button wire:click="darDeBajaConfirmado"
                        class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">Confirmar</button>
                </div>
            </div>
        </div>
    @endif
</div>