{{-- resources/views/livewire/dataentry/_form-individual.blade.php --}}
@if ($bienSeleccionado && !$grupoSeleccionado)
<div id="form-documentacion">
    @php
        $bien = App\Models\Bien::with('remito')->find($bienSeleccionado);
    @endphp

    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
        <h3 class="font-semibold text-gray-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Documentación para el Bien {{ $bien->numero_inventario }}
        </h3>

        <form wire:submit.prevent="guardarDocumentacion">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número de Acta</label>
                    <input type="text" wire:model="numero_acta" placeholder="Ej: ACTA-2025-0001"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Acta</label>
                    <input type="date" wire:model="fecha_acta"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número de Resolución</label>
                    <input type="text" wire:model="numero_resolucion" placeholder="Ej: RES-2025-0045"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número de Factura</label>
                    <input type="text" wire:model="numero_factura" placeholder="Ej: FAC-A-0000001"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Factura</label>
                    <input type="date" wire:model="fecha_factura"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Monto</label>
                    <input type="number" step="0.01" wire:model="monto"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Partida Presupuestaria</label>
                    <input type="text" wire:model="partida_presupuestaria" placeholder="Ej: 3.2.1 o 293-45"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Orden de Pago</label>
                    <input type="text" wire:model="orden_pago" placeholder="Ej: OP-2025-0009"
                        class="w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
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
                <button type="button" wire:click="$set('bienSeleccionado', null)"
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-900 text-white rounded-lg hover:bg-indigo-800 transition-colors flex items-center gap-2">
                    Guardar Documentación
                </button>
            </div>
        </form>
    </div>
</div>
@endif