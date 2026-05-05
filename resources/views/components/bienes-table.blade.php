{{-- resources/views/livewire/receptor/components/bienes-table.blade.php --}}
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
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
                    <th class="px-4 py-2 text-left font-semibold">Foto</th>
                    <th class="px-4 py-2 text-center font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($bienes as $index => $bien)
                    @include('livewire.receptor.components.bien-row', ['bien' => $bien, 'index' => $index])
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 flex items-center gap-4 px-2 py-3">
            <div class="hidden-message flex items-center">
                {{ $bienes->links() }}
            </div>
            <div class="text-sm text-gray-600 whitespace-nowrap">
                Mostrando {{ $bienes->firstItem() }} a {{ $bienes->lastItem() }} de {{ $bienes->total() }} resultados
            </div>
        </div>
    </div>
</div>