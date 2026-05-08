{{-- resources/views/livewire/dataentry/_modal-exportar.blade.php --}}
<div x-data="{ open: @entangle('showExportModal') }" x-show="open"
    class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">

    <div @click.away="open = false"
        class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Exportar a Excel por Fechas</h2>

        <form wire:submit.prevent="exportarExcelPorFechas">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-700">Fecha Inicial</label>
                    <input type="date" wire:model="fechaInicio" required class="border rounded w-full p-2">
                    @error('fechaInicio') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Fecha Final</label>
                    <input type="date" wire:model="fechaFin" required class="border rounded w-full p-2">
                    @error('fechaFin') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end mt-6 space-x-3">
                <button type="button" @click="open = false"
                    class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-700 text-white rounded hover:bg-indigo-800 flex items-center gap-2">
                    <i class="bi bi-download"></i> Exportar
                </button>
                <button type="button" wire:click="exportarTodo"
                    class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800">
                    Exportar Todo
                </button>
            </div>
        </form>
    </div>
</div>