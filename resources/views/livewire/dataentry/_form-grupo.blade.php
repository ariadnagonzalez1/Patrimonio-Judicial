{{-- resources/views/livewire/dataentry/_form-grupo.blade.php --}}
@if ($grupoSeleccionado)
<div id="form-documentacion">
    @php
        $grupo = $this->pendientes->get($grupoSeleccionado);
    @endphp

    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
        <h3 class="font-semibold text-gray-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Documentación para {{ $grupo['cantidad'] }} Bienes
        </h3>

        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 p-4 rounded-lg mb-6 border border-indigo-100">
            <p class="font-semibold text-gray-900 text-lg mb-2">Grupo Seleccionado</p>
            <div class="flex gap-3 text-sm text-gray-700">
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded">
                    📋 Remito: {{ $grupo['numero_remito'] }}
                </span>
                <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded">
                    📁 Expediente: {{ $grupo['numero_expediente'] }}
                </span>
                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded">
                    🧾 O.P.: {{ $grupo['orden_provision'] }}
                </span>
            </div>
            <p class="text-sm text-gray-600 mt-2">
                La documentación se aplicará a los {{ $grupo['cantidad'] }} bienes de este grupo
            </p>
        </div>

        <form wire:submit.prevent="guardarDocumentacionGrupo">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número de Acta</label>
                    <input type="text" wire:model="numero_acta" placeholder="Ej: ACTA-2024-0000 o 5849/25"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('numero_acta') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Acta</label>
                    <input type="date" wire:model="fecha_acta"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número de Resolución</label>
                    <input type="text" wire:model="numero_resolucion" placeholder="Ej: RES-2024-0000 o 4892-42"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('numero_resolucion') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número de Factura</label>
                    <input type="text" wire:model="numero_factura" placeholder="Ej: FAC-A-0000000 o 43295"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('numero_factura') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Factura</label>
                    <input type="date" wire:model="fecha_factura"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Monto</label>
                    <input type="number" wire:model="monto" step="0.01" placeholder="0.00"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Partida Presupuestaria</label>
                    <input type="text" wire:model="partida_presupuestaria" placeholder="Ej: 2.9.3 o 4892-42"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('partida_presupuestaria') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Orden de Pago</label>
                    <input type="text" wire:model="orden_pago" placeholder="Ej: PO-2024-0000 o 5849/25"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('orden_pago') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select wire:model="estado"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="pendiente">Pendiente</option>
                        <option value="completo">Completo</option>
                        <option value="revisado">Revisado</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                <textarea wire:model="observaciones" rows="3"
                    placeholder="Observaciones adicionales..."
                    class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <div class="flex justify-end mt-6 gap-2">
                <button type="button" wire:click="$set('grupoSeleccionado', null)"
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-900 text-white rounded-lg hover:bg-indigo-800 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Guardar para {{ count($bienesDelGrupo) }} Bienes
                </button>
            </div>
        </form>
    </div>
</div>
@endif