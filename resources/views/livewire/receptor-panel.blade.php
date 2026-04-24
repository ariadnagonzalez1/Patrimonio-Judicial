{{-- resources/views/livewire/receptor-panel.blade.php --}}
<div>
    
    <style>
        .hidden-message nav > div > div:first-child {
            display: none !important;
        }
    </style>

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Panel de Receptor</h1>
        <p class="text-gray-600 mt-1">Registro y alta de bienes patrimoniales</p>
    </div>

    <!-- Formulario de Registro -->
    <div class="bg-gray-50 rounded-lg p-6">

        <div class="mb-6">
            <div class="flex items-center space-x-2 mb-2">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <h2 class="text-xl font-semibold text-gray-900">Registro de Nuevo Bien</h2>
            </div>
            <p class="text-sm text-gray-600">Complete los datos obligatorios para dar de alta un nuevo bien</p>
        </div>

        @if($mostrarRegistros)
        <div class="bg-white rounded-lg shadow p-6">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 7h18M3 12h18m-9 5h9"/>
                    </svg>
                    Bienes Registrados
                </h2>

                <button wire:click="volverAlFormulario" 
                    class="flex items-center gap-2 px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span>Volver</span>
                </button>
            </div>

            <!-- 🔥 CONTENEDOR RESPONSIVE -->
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
                            <th class="px-4 py-2 text-center font-semibold">Foto del Bien</th>

                            {{-- ✅ NUEVO: columna acciones --}}
                            <th class="px-4 py-2 text-center font-semibold">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @foreach($bienes as $index => $bien)
                        <tr>
                            <td class="px-4 py-2">{{ $index + 1 }}</td>
                            <td class="px-4 py-2">{{ $bien->remito->numero_expediente ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $bien->remito->orden_provision ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $bien->remito->numero_remito ?? '-' }}</td>

                            <td class="px-4 py-2">
                                {{ $bien->cuenta->codigo ?? '-' }}
                            </td>

                            <td class="px-4 py-2">{{ $bien->numero_inventario ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $bien->descripcion ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $bien->cantidad }}</td>

                            <td class="px-4 py-2">${{ number_format($bien->precio_unitario, 2, ',', '.') }}</td>
                            <td class="px-4 py-2">${{ number_format($bien->monto_total, 2, ',', '.') }}</td>

                            <td class="px-4 py-2">{{ $bien->proveedor->razon_social ?? '-' }}</td>

                            <td class="px-4 py-2">
                                {{ optional($bien->remito)->fecha_recepcion
                                    ? \Carbon\Carbon::parse($bien->remito->fecha_recepcion)->format('d/m/Y')
                                    : '-' }}
                            </td>

                            <td class="px-4 py-2 text-sm text-gray-800 text-center">
                                @if ($bien->foto)
                                    <a href="{{ asset('storage/' . $bien->foto) }}" target="_blank" class="inline-block">
                                        <img src="{{ asset('storage/' . $bien->foto) }}" 
                                            alt="Foto del Bien" 
                                            class="w-14 h-14 object-cover rounded-md border border-gray-300 shadow-sm hover:scale-105 transition-transform duration-200 ease-out">
                                    </a>
                                @else
                                    <span class="text-gray-400 italic">Sin foto</span>
                                @endif
                            </td>

                            <td class="px-4 py-2 text-center">
                            <div class="flex gap-2 items-center justify-center">
                                <button
                                    type="button"
                                    wire:click="editarBien({{ $bien->id }})"
                                    style="background-color: #f59e0b; color: white; padding: 6px 12px; border-radius: 4px; font-size: 14px; cursor: pointer;"
                                    onmouseover="this.style.backgroundColor='#d97706'"
                                    onmouseout="this.style.backgroundColor='#f59e0b'">
                                    Editar
                                </button>
                            
                                <button
                                    type="button"
                                    onclick="confirmarEliminacion({{ $bien->id }})"
                                    style="background-color: #dc2626; color: white; padding: 6px 12px; border-radius: 4px; font-size: 14px; cursor: pointer;"
                                    onmouseover="this.style.backgroundColor='#b91c1c'"
                                    onmouseout="this.style.backgroundColor='#dc2626'">
                                    Eliminar
                                </button>
                            </div>
                        </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>

                <div class="mt-4 flex items-center gap-4 px-2">

                    <!-- Botones -->
                    <div class="hidden-message flex items-center">
                        {{ $bienes->links() }}
                    </div>

                    <!-- Texto pegado a los botones -->
                    <div class="text-sm text-gray-600 whitespace-nowrap">
                        Showing {{ $bienes->firstItem() }} to {{ $bienes->lastItem() }} of {{ $bienes->total() }} results
                    </div>

                </div>

            </div>

        </div>
        @endif


        <form wire:submit.prevent="registrarBien">
            <!-- 🔹 Datos del Remito -->
            <!-- 🔹 Botón Ver Registros -->
            <div class="flex justify-end mb-4">
                <button 
                    type="button" 
                    wire:click="verRegistros"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 text-white"
                        viewBox="0 0 20 20"
                        fill="currentColor">
                        <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V7.414A2 2 0 0017.414 6L14 2.586A2 2 0 0012.586 2H4z"/>
                    </svg>

                    <span>Ver Registros</span>
                </button>
            </div>

            <div class="mb-6">
                <!-- Encabezado -->

                <!-- Recuadro con los tres campos -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 shadow-sm">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M7 8h10M7 12h10m-5 4h5m2 4H5a2 2 0 01-2-2V6a2 2 0 
                                    012-2h9l5 5v11a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="text-base font-semibold text-gray-800">Datos del Remito</h3>
                    </div>
                    <div class="grid grid-cols-3 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-700">
                                N° de Remito<span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model.live="numero_remito"
                                placeholder="REM-2024-0000"
                                class="w-full mt-1 border-gray-200 bg-white rounded-md
                                    @if($alerta_remito) border-red-500 @endif
                                    focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @if($alerta_remito)
                                <p class="text-sm text-red-600 mt-1">{{ $alerta_remito }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">
                                N° de Expediente<span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model.live="numero_expediente"
                                placeholder="EXP-2024-0000"
                                class="w-full mt-1 border-gray-200 bg-white rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @if($alerta_expediente)
                                <p class="text-sm text-red-600 mt-1">{{ $alerta_expediente }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">
                                Orden de Provisión<span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model.live="orden_provision"
                                placeholder="OP-2024-0000"
                                class="w-full mt-1 border-gray-200 bg-white rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @if($alerta_orden)
                                <p class="text-sm text-red-600 mt-1">{{ $alerta_orden }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- 🔹 Información del Bien -->
            <div class="mb-6">
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 shadow-sm space-y-6">

                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7h18M3 12h18m-9 5h9"/>
                            </svg>
                            <h3 class="text-base font-semibold text-gray-800">Información del Bien</h3>
                        </div>

                        <!-- 🔘 Botón para agregar nuevo bien -->
                        <button 
                            type="button"
                            wire:click="agregarFormulario"
                            class="flex items-center gap-1 px-3 py-1 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                            <span>Agregar</span>
                        </button>
                    </div>

                    <!-- 🔁 Se repite un formulario por cada bien -->
                    @foreach($formularios as $index => $form)
                    {{-- 🔑 ¡CRÍTICO! wire:key usa el ID único del formulario --}}
                    <div wire:key="form-{{ $form['id'] }}" class="border border-gray-200 rounded-lg p-5 bg-white space-y-6 relative">

                        <!-- 🗑️ Botón para eliminar formulario -->
                        @if(count($formularios) > 1)
                            <button 
                                type="button"
                                wire:click="eliminarFormulario({{ $form['id'] }})"
                                class="absolute top-2 right-2 flex items-center gap-1 px-2 py-1 text-xs bg-red-50 text-red-600 border border-red-200 rounded-md hover:bg-red-100 hover:text-red-700 transition"
                                title="Eliminar este bien">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-9 0h10"/>
                                </svg>
                                <span>Eliminar</span>
                            </button>
                        @endif

                        <div class="grid grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cuenta del Bien *</label>
                                @php
                                    $mapCuentas = $cuentas->mapWithKeys(fn($c) => [
                                        $c->codigo . ' - ' . $c->descripcion => $c->id
                                    ]);
                                @endphp

                                <div>
                                    <input
                                        type="text"
                                        list="cuentas-list-{{ $index }}"
                                        placeholder="Buscar cuenta..."
                                        class="w-full px-3 py-2 border-gray-200 bg-white rounded-md
                                            focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        onchange="
                                            const map = @js($mapCuentas);
                                            const val = this.value;
                                            if (map[val]) {
                                                @this.set('formularios.{{ $index }}.cuenta_id', map[val]);
                                            }
                                        "
                                    >

                                    <datalist id="cuentas-list-{{ $index }}">
                                        @foreach($cuentas as $cuenta)
                                            <option value="{{ $cuenta->codigo }} - {{ $cuenta->descripcion }}"></option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">N° Inventario Inicial *</label>
                                <input 
                                    type="text" 
                                    wire:model.live="formularios.{{ $index }}.numero_inventario"
                                    placeholder="Ej: RG-001-9743"
                                    class="w-full px-3 py-2 border-gray-200 bg-white rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @if(isset($alertas_inventario[$index]))
                                    <p class="text-sm text-red-600 mt-1">
                                        {{ $alertas_inventario[$index] }}
                                    </p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad *</label>
                                <input
                                    type="number" 
                                    wire:model.live="formularios.{{ $index }}.cantidad"
                                    min="1"
                                    class="w-full px-3 py-2 border-gray-200 bg-white rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Ej: 5">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción *</label>
                            <textarea 
                                wire:model="formularios.{{ $index }}.descripcion"
                                rows="2"
                                placeholder="Ej: Silla ergonómica negra con apoyabrazos"
                                class="w-full px-3 py-2 border-gray-200 bg-white rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Precio Unitario *</label>
                                <input 
                                    type="number" 
                                    wire:model.lazy="formularios.{{ $index }}.precio_unitario"
                                    step="0.01"
                                    min="0"
                                    placeholder="Ej: 1500.00"
                                    class="w-full px-3 py-2 border-gray-200 bg-white rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Monto Total *</label>
                                <input 
                                    type="number" 
                                    wire:model="formularios.{{ $index }}.monto_total"
                                    readonly
                                    class="w-full px-3 py-2 border-gray-200 bg-gray-100 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Recepción *</label>
                                <input 
                                    type="date" 
                                    wire:model="formularios.{{ $index }}.fecha_recepcion"
                                    class="w-full px-3 py-2 border-gray-200 bg-white rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Proveedor *</label>
                                <select 
                                    wire:model="formularios.{{ $index }}.proveedor_id"
                                    class="w-full px-3 py-2 border-gray-200 bg-white rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Seleccione proveedor</option>
                                    @foreach($proveedores as $proveedor)
                                        <option value="{{ $proveedor->id }}">{{ $proveedor->razon_social }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Foto del Bien</label>
                                <div class="flex flex-col space-y-2">
                                    <input 
                                        type="file" 
                                        wire:model="fotos.{{ $form['id'] }}"
                                        accept="image/*"
                                        class="w-full px-3 py-2 border-gray-200 bg-white rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 mb-4">
                                    @if (isset($fotos[$form['id']]))
                                        <div class="mt-2">
                                            <img src="{{ $fotos[$form['id']]->temporaryUrl() }}" 
                                                class="w-32 h-32 object-cover rounded-lg border">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Bien *</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input 
                                            type="radio" 
                                            wire:model="formularios.{{ $index }}.tipo_bien"
                                            value="uso"
                                            class="w-4 h-4 text-indigo-600 focus:ring-indigo-500"
                                            checked>
                                        <span class="ml-2 text-sm text-gray-700">Bien de Uso</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input 
                                            type="radio" 
                                            wire:model="formularios.{{ $index }}.tipo_bien"
                                            value="consumo"
                                            class="w-4 h-4 text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Bien de Consumo</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Compra</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input 
                                            type="checkbox" 
                                            wire:model="formularios.{{ $index }}.compra_licitacion"
                                            class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 rounded">
                                        <span class="ml-2 text-sm text-gray-700">Compra por Licitación</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1">Si no está marcado, se considera Compra Directa</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-3">
                <button 
                    type="button"
                    wire:click="cancelar"
                    class="px-6 py-2 rounded-md bg-red-600 text-white font-medium 
                        hover:bg-red-700 transition-colors flex items-center justify-center">
                    Cancelar
                </button>

                <button 
                    type="submit"
                    class="px-6 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800 font-medium transition-colors flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Registrar Bien</span>
                </button>
            </div>
        </form>
    </div>

    {{-- ✅ NUEVO: Modal de modificación (no toca lo anterior, solo aparece si $editandoBien tiene contenido) --}}
    @if($editandoBien)
<div class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">

    {{-- Caja del modal --}}
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-5xl overflow-hidden">

        {{-- Header fijo --}}
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Modificar Bien</h2>

            <button
                type="button"
                wire:click="$set('editandoBien', null)"
                class="text-gray-500 hover:text-gray-700 text-sm">
                Cerrar
            </button>
        </div>

        {{-- Body con scroll interno --}}
        <div class="px-6 py-5 max-h-[70vh] overflow-y-auto">
            {{-- ✅ ACA ADENTRO VA TU CONTENIDO (los bloques que ya armamos) --}}
            
            {{-- ======= DATOS ADMINISTRATIVOS (solo lectura) ======= --}}
            <div class="bg-gray-50 border rounded-lg p-4 mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Datos Administrativos</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="text-xs text-gray-500">Remito</label>
                        <input type="text" value="{{ $editandoBien['numero_remito'] ?? '' }}" readonly
                               class="w-full mt-1 px-2 py-2 border rounded bg-gray-100 text-sm">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Expediente</label>
                        <input type="text" value="{{ $editandoBien['numero_expediente'] ?? '' }}" readonly
                               class="w-full mt-1 px-2 py-2 border rounded bg-gray-100 text-sm">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Orden de Provisión</label>
                        <input type="text" value="{{ $editandoBien['orden_provision'] ?? '' }}" readonly
                               class="w-full mt-1 px-2 py-2 border rounded bg-gray-100 text-sm">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Inventario</label>
                        <input type="text" value="{{ $editandoBien['numero_inventario'] ?? '' }}" readonly
                               class="w-full mt-1 px-2 py-2 border rounded bg-gray-100 text-sm">
                    </div>
                </div>
            </div>

            {{-- ======= DATOS DEL BIEN (editables) ======= --}}
            <div class="bg-white border rounded-lg p-4 mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Datos del Bien</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-700">Cuenta</label>
                        <select wire:model.defer="editandoBien.cuenta_id"
                                class="w-full mt-1 border rounded px-3 py-2">
                            <option value="">Seleccione cuenta</option>
                            @foreach($cuentas as $cuenta)
                                <option value="{{ $cuenta->id }}">
                                    {{ $cuenta->codigo }} - {{ $cuenta->descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm text-gray-700">Proveedor</label>
                        <select wire:model.defer="editandoBien.proveedor_id"
                                class="w-full mt-1 border rounded px-3 py-2">
                            <option value="">Seleccione proveedor</option>
                            @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->id }}">{{ $proveedor->razon_social }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-sm text-gray-700">Descripción</label>
                        <textarea wire:model.defer="editandoBien.descripcion"
                                  class="w-full mt-1 border rounded px-3 py-2"
                                  rows="3"></textarea>
                    </div>

                    <div>
                        <label class="text-sm text-gray-700">Fecha de Recepción</label>
                        <input type="date"
                               wire:model.defer="editandoBien.fecha_recepcion"
                               class="w-full mt-1 border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="text-sm text-gray-700">Tipo de Bien</label>
                        <select wire:model.defer="editandoBien.tipo_bien"
                                class="w-full mt-1 border rounded px-3 py-2">
                            <option value="uso">Uso</option>
                            <option value="consumo">Consumo</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" wire:model.defer="editandoBien.compra_licitacion">
                            Compra por licitación
                        </label>
                    </div>
                </div>
            </div>

            {{-- ======= DATOS ECONÓMICOS (no editables) ======= --}}
            <div class="bg-gray-100 border rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Datos Económicos (no editables)</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm text-gray-700">Cantidad</label>
                        <input type="number" wire:model="editandoBien.cantidad" readonly
                               class="w-full mt-1 border rounded px-3 py-2 bg-gray-200 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="text-sm text-gray-700">Precio Unitario</label>
                        <input type="number" wire:model="editandoBien.precio_unitario" readonly
                               class="w-full mt-1 border rounded px-3 py-2 bg-gray-200 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="text-sm text-gray-700">Monto Total</label>
                        <input type="number" wire:model="editandoBien.monto_total" readonly
                               class="w-full mt-1 border rounded px-3 py-2 bg-gray-200 cursor-not-allowed">
                    </div>
                </div>
            </div>

        </div>

        {{-- Footer fijo --}}
        <div class="px-6 py-4 border-t flex justify-end gap-3 bg-white">
            <button
                type="button"
                wire:click="$set('editandoBien', null)"
                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                Cancelar
            </button>

            <button
                type="button"
                wire:click="guardarEdicion"
                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                Guardar Cambios
            </button>
        </div>

    </div>
</div>
@endif

    {{-- Contenedor de Alertas dinámico --}}
    <div id="flash-container" class="fixed bottom-4 right-4 z-[9999] flex flex-col gap-3">
        @if (session()->has('message'))
            <div class="custom-alert bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-xl flex items-center justify-between" style="min-width: 300px;">
                <span>{{ session('message') }}</span>
                <button onclick="this.parentElement.remove()" class="ml-4 font-bold">✕</button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="custom-alert bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-xl flex items-center justify-between" style="min-width: 300px;">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="ml-4 font-bold">✕</button>
            </div>
        @endif
    </div>

    <script>
        (function() {
            // Función para cerrar las alertas
            const closeAlerts = () => {
                const alerts = document.querySelectorAll('.custom-alert');
                alerts.forEach(alert => {
                    // Solo programamos el cierre si no tiene ya una clase de "salida"
                    if (!alert.dataset.timerStarted) {
                        alert.dataset.timerStarted = "true";
                        setTimeout(() => {
                            alert.style.opacity = '0';
                            alert.style.transition = 'opacity 0.5s ease';
                            setTimeout(() => alert.remove(), 500);
                        }, 4000);
                    }
                });
            };

            // Ejecutar inmediatamente
            closeAlerts();

            // OBSERVER: Esta es la clave. Vigila si Livewire mete algo nuevo al DOM
            const observer = new MutationObserver((mutations) => {
                closeAlerts();
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        })();
    </script>

    {{-- ✅ NUEVO: Confirmación de eliminación (pregunta "¿estás segura?") --}}
    <script>
        function confirmarEliminacion(bienId) {
            if (confirm('¿Estás segura de que querés eliminar este bien? Esta acción no se puede deshacer.')) {
                Livewire.dispatch('eliminarBienConfirmado', { id: bienId });
            }
        }
    </script>
    
</div>
