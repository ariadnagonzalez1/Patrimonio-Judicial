@if ($modalMantenimiento && $bienSeleccionado)
<div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-6">
    <div class="bg-white w-full max-w-xl rounded-xl shadow-xl relative overflow-hidden">

        {{-- Cerrar --}}
        <button wire:click="$set('modalMantenimiento', false)"
                class="absolute right-4 top-4 text-gray-500 hover:text-black">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="p-6 space-y-6">

            <h2 class="text-xl font-bold flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke-width="1.8"
                     stroke="currentColor" class="w-6 h-6 text-indigo-600">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M14.121 14.121L21 21M11 3l-1.879 1.879a3 3 0 000 4.242L14 14l3-3-4.879-4.879a3 3 0 00-4.242 0L3 11" />
                </svg>
                Marcar para Arreglo/Mantenimiento
            </h2>

            <p class="text-gray-600 text-sm">
                Registrar necesidad de mantenimiento para {{ $bienSeleccionado->numero_inventario }}
            </p>

            <div class="p-4 bg-gray-50 border rounded-lg">
                <p class="font-semibold">{{ $bienSeleccionado->descripcion }}</p>
                <p class="text-sm text-gray-600">{{ $bienSeleccionado->numero_inventario }}</p>
            </div>

            {{-- Motivo --}}
            <div>
                <label class="block font-semibold text-sm mb-1">Motivo del Mantenimiento*</label>
                <textarea wire:model.defer="motivo_mantenimiento"
                          class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2"
                          rows="3"
                          placeholder="Describa el problema o necesidad de mantenimiento..."></textarea>
                @error('motivo_mantenimiento')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Fecha estimada --}}
            <div>
                <label class="block font-semibold text-sm mb-1">Fecha Estimada de Resolución</label>
                <input type="date"
                       wire:model.defer="fecha_resolucion"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('fecha_resolucion')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Botones --}}
            <div class="flex justify-end gap-2 mt-4">
                <button wire:click="$set('modalMantenimiento', false)"
                        class="px-4 py-2 rounded-md border text-gray-700 hover:bg-gray-100">
                    Cancelar
                </button>

                <button wire:click="guardarMantenimiento"
                        class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.8"
                         stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M14.121 14.121L21 21M11 3l-1.879 1.879a3 3 0 000 4.242L14 14l3-3-4.879-4.879a3 3 0 00-4.242 0L3 11" />
                    </svg>
                    Registrar Mantenimiento
                </button>
            </div>

        </div>
    </div>
</div>
@endif
