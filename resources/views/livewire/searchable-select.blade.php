{{-- resources/views/livewire/searchable-select.blade.php --}}
<div x-data="{ open: false }" class="relative w-full">
    <!-- Input de búsqueda -->
    <input type="text"
           wire:model.live.debounce.300ms="search"
           @focus="open = true"
           @click.away="open = false"
           placeholder="{{ $placeholder }}"
           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
    
    <!-- Lista desplegable de opciones -->
    <div x-show="open && ('{{ count($this->filteredOptions) }}' > 0)"
         x-cloak
         class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
        <div class="py-1">
            @foreach($this->filteredOptions as $option)
                <div class="px-3 py-2 cursor-pointer hover:bg-gray-100 transition"
                     wire:click="selectOption({{ $option['id'] }})"
                     @click="open = false">
                    {{ $option['text'] }}
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- Mostrar selección actual -->
    @if($selectedId)
        @php
            $selectedOption = collect($options)->firstWhere('id', $selectedId);
        @endphp
        @if($selectedOption)
            <div class="mt-1 text-xs text-green-600">
                ✅ Seleccionado: {{ $selectedOption['text'] }}
            </div>
        @endif
    @endif
</div>

<style>
    [x-cloak] { display: none !important; }
</style>