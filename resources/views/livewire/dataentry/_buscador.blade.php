{{-- resources/views/livewire/dataentry/_buscador.blade.php --}}
<div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
    <h3 class="font-semibold flex items-center gap-2 mb-4 text-gray-800">
        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        Buscar Bien
    </h3>

    <div>
        <label class="text-sm font-medium text-gray-700">
            Número de Remito u Orden de Provisión
        </label>
        <input type="text"
            wire:model.defer="busqueda"
            placeholder="Ej: REM-1234 o OP-5849/25"
            class="w-full mt-1 px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
    </div>

    <button wire:click="buscar"
        class="w-full mt-4 bg-indigo-900 text-white rounded-lg py-2 hover:bg-indigo-800 transition-colors font-medium">
        Buscar
    </button>
</div>