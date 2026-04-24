<div class="space-y-6">

    {{-- 🔹 Encabezado --}}
    <h2 class="text-2xl font-bold text-gray-900">Histórico de Bienes</h2>

    {{-- 🔹 Filtros --}}
    <div class="bg-white p-4 border rounded-lg shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- ESTADO --}}
            <div>
                <label class="text-sm font-semibold text-gray-600">Estado</label>
                <select wire:model.live="estado"
                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="todos">Todos</option>
                    <option value="stock">Stock</option>
                    <option value="asignado">Asignado</option>
                    <option value="baja">Baja</option>
                </select>
            </div>

            {{-- PER PAGE --}}
            <div>
                <label class="text-sm font-semibold text-gray-600">Mostrar</label>
                <select wire:model.live="perPage"
                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="10">10 por página</option>
                    <option value="25">25 por página</option>
                    <option value="50">50 por página</option>
                </select>
            </div>

        </div>
    </div>

    {{-- 🔹 Tabla --}}
    <div class="bg-white border rounded-lg shadow-sm overflow-hidden">

        @if($bienes->count())

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr class="text-left text-xs font-semibold text-gray-600 uppercase">
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Orden prov.</th>
                        <th class="px-4 py-3">Cuenta</th>
                        <th class="px-4 py-3">Inventario</th>
                        <th class="px-4 py-3">Detalle</th>
                        <th class="px-4 py-3 text-right">P. Unitario</th>
                        <th class="px-4 py-3 text-right">Total</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @foreach ($bienes as $bien)
                    <tr class="hover:bg-gray-50">

                        {{-- FECHA --}}
                        <td class="px-4 py-3">
                            {{ optional($bien->remito)->fecha_recepcion
                                ? \Carbon\Carbon::parse($bien->remito->fecha_recepcion)->format('d/m/Y')
                                : 'N/A' }}
                        </td>

                        {{-- ORDEN PROVISIÓN --}}
                        <td class="px-4 py-3">{{ optional($bien->remito)->orden_provision }}</td>

                        {{-- CUENTA --}}
                        <td class="px-4 py-3">{{ optional($bien->cuenta)->codigo }}</td>

                        {{-- INVENTARIO --}}
                        <td class="px-4 py-3 font-semibold">
                            {{ $bien->numero_inventario }}
                        </td>

                        {{-- DETALLE --}}
                        <td class="px-4 py-3">{{ $bien->descripcion }}</td>

                        {{-- PRECIO UNITARIO --}}
                        <td class="px-4 py-3 text-right">
                            $ {{ number_format($bien->precio_unitario, 2, ',', '.') }}
                        </td>

                        {{-- TOTAL --}}
                        <td class="px-4 py-3 text-right font-semibold">
                            $ {{ number_format($bien->monto_total, 2, ',', '.') }}
                        </td>

                        {{-- ESTADO --}}
                        <td class="px-4 py-3">
                            @php
                                $color = match($bien->estado) {
                                    'stock' => 'bg-blue-100 text-blue-800',
                                    'asignado' => 'bg-emerald-100 text-emerald-800',
                                    'baja' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp

                            <span class="px-2 py-1 rounded-full text-xs font-bold {{ $color }}">
                                {{ ucfirst($bien->estado) }}
                            </span>
                        </td>

                        {{-- ACCIONES --}}
                        <td class="px-4 py-3 text-center">
    <div class="flex items-center justify-center gap-2">

        {{-- 👁 Ver detalle --}}
        <button wire:click="verDetalle({{ $bien->id }})"
            class="p-2 bg-gray-100 rounded hover:bg-gray-200"
            title="Ver detalles">
            <svg xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"
                class="w-5 h-5 text-gray-700">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75
                       7.5-9.75 7.5S2.25 12 2.25 12z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </button>

        {{-- 🔨🔧 Mantenimiento --}}
        <button wire:click="abrirMantenimiento({{ $bien->id }})"
            title="Registrar mantenimiento"
            class="p-2 bg-gray-100 rounded hover:bg-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"
                 class="w-5 h-5 text-gray-700">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M14.121 14.121L21 21M11 3l-1.879 1.879a3 3 0 000 4.242L14 14l3-3-4.879-4.879a3 3 0 00-4.242 0L3 11" />
            </svg>
        </button>

        {{-- 🔁 Reasignación --}}
<button wire:click="abrirReasignacion({{ $bien->id }})"
        title="Reasignar"
        class="p-2 bg-gray-100 rounded hover:bg-gray-200">
    <svg xmlns="http://www.w3.org/2000/svg"
         fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"
         class="w-5 h-5 text-gray-700">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M4 7h13m0 0l-4-4m4 4l-4 4M20 17H7m0 0l4-4m-4 4l4 4" />
    </svg>
</button>

        {{-- 🗑 Dar de baja --}}
<button wire:click="abrirBaja({{ $bien->id }})"
        title="Dar de baja"
        class="p-2 bg-gray-100 rounded hover:bg-gray-200">
    <svg xmlns="http://www.w3.org/2000/svg"
         fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"
         class="w-5 h-5 text-red-600">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M6 7h12M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m5 
               0v12a2 2 0 01-2 2H6a2 2 0 01-2-2V7h16z" />
    </svg>
</button>

    </div>
</td>


                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- 🔹 Modal Detalle del Bien --}}
        @include('livewire.admin.partials.detalle-bien')
        @include('livewire.admin.partials.mantenimiento')
        @include('livewire.admin.partials.reasignacion')
        @include('livewire.admin.partials.baja')

        {{-- 🔹 Paginación --}}
        <div class="px-4 py-3 bg-gray-50 border-t">
            {{ $bienes->links() }}
        </div>

        @else
        <div class="p-6 text-center text-gray-500">
            No se encontraron bienes.
        </div>
        @endif

    </div>

</div>
