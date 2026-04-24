<div class="space-y-8">
    {{-- 🔹 ENCABEZADO --}}
    <div class="flex items-center justify-between mb-2">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Panel de Administración</h1>
            <p class="text-gray-600">Gestión centralizada del sistema patrimonial</p>
        </div>
    </div>

    {{-- 🔹 TABS --}}
    <div class="flex gap-2 border-b border-gray-200">
        @php
            $tabs = [
                'usuarios' => ['icon' => 'bi bi-people', 'label' => 'Usuarios'],
                'proveedores' => ['icon' => 'bi bi-building', 'label' => 'Proveedores'],
                'bienes' => ['icon' => 'bi bi-box-seam', 'label' => 'Bienes'],
                'dependencias' => ['icon' => 'bi bi-house-door', 'label' => 'Dependencias'],
                'accesos' => ['icon' => 'bi bi-key', 'label' => 'Accesos a Paneles'],
                'historico' => ['icon' => 'bi bi-arrow-counterclockwise', 'label' => 'Histórico'],
            ];
        @endphp

        @foreach ($tabs as $key => $tab)
            <button wire:click="$set('activeTab', '{{ $key }}')"
                class="flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-t-md transition-colors 
                    @if($activeTab === $key)
                        bg-gray-900 text-white shadow-md
                    @else
                        text-gray-700 hover:text-gray-900 hover:bg-gray-100
                    @endif">
                <i class="{{ $tab['icon'] }}"></i>
                <span>{{ $tab['label'] }}</span>
            </button>
        @endforeach
    </div>

    {{-- 🔹 CONTENIDO --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
        @if ($activeTab === 'usuarios')
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-people text-indigo-600"></i> Gestión de Usuarios
                </h2>
                <p class="text-sm text-gray-500 mb-4">Crea, edita y administra los usuarios del sistema y sus roles.</p>
                @livewire('admin.usuarios')
            </div>

        @elseif ($activeTab === 'proveedores')
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-building text-indigo-600"></i> Gestión de Proveedores
                </h2>
                <p class="text-sm text-gray-500 mb-4">Administra los proveedores registrados y su información de contacto.</p>
                @livewire('admin.proveedores')
            </div>

        @elseif ($activeTab === 'bienes')
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-box-seam text-indigo-600"></i> Bienes Patrimoniales
                </h2>
                <p class="text-sm text-gray-500 mb-4">Consulta y gestiona el inventario general del sistema.</p>
                @livewire('admin.bienes')
            </div>

        @elseif ($activeTab === 'dependencias')
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-house-door text-indigo-600"></i> Dependencias
                </h2>
                <p class="text-sm text-gray-500 mb-4">Gestiona las dependencias y ubicaciones del Poder Judicial.</p>
                @livewire('admin.dependencias')
            </div>

        @elseif ($activeTab === 'accesos')
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-key text-indigo-600"></i> Accesos a Paneles
                </h2>
                <p class="text-sm text-gray-500 mb-6">Desde aquí puedes acceder directamente a los paneles de cada rol del sistema.</p>

                {{-- TARJETAS DE ACCESO --}}
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach ($paneles as $panel)
                        <div class="border border-gray-200 rounded-xl p-5 bg-gradient-to-b from-gray-50 to-white shadow-sm hover:shadow-md transition-all">
                            <div class="flex items-start gap-3">
                                <div class="text-3xl">{{ $panel['icono'] }}</div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $panel['nombre'] }}</h3>
                                    <p class="text-sm text-gray-600">{{ $panel['descripcion'] }}</p>
                                </div>
                            </div>

                            <div class="mt-4 flex justify-end">
                                <button wire:click="redirigir('{{ $panel['ruta'] }}')"
                                    class="flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-md text-sm hover:bg-gray-800 transition">
                                    <i class="bi bi-arrow-right-circle"></i> Ir al panel
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @elseif ($activeTab === 'historico')
    <div>
        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-arrow-counterclockwise text-indigo-600"></i> Gestión de Histórico
        </h2>
        <p class="text-sm text-gray-500 mb-4">
            Consulta completa del ciclo de vida de los bienes patrimoniales.
        </p>

        {{-- Cargamos el componente Livewire --}}
        @livewire('admin.historico-bienes')
    </div>

        @endif
    </div>
</div>
