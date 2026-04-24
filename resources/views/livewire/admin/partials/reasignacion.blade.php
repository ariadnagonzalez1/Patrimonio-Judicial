@if ($modalReasignacion && $bienSeleccionado)
<div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-6">
    <div class="bg-white w-full max-w-xl rounded-xl shadow-xl relative overflow-hidden">

        {{-- BOTÓN CERRAR --}}
        <button wire:click="$set('modalReasignacion', false)"
                class="absolute right-4 top-4 text-gray-500 hover:text-black">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- CONTENIDO --}}
        <div class="p-6 space-y-6">

            {{-- TÍTULO --}}
            <h2 class="text-xl font-bold flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke-width="1.8"
                     stroke="currentColor" class="w-6 h-6 text-indigo-600">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 7h13m0 0l-4-4m4 4l-4 4M20 17H7m0 0l4-4m-4 4l4 4" />
                </svg>
                Reasignar Bien
            </h2>

            <p class="text-gray-600 text-sm">
                Reasignar {{ $bienSeleccionado->numero_inventario }} a una nueva dependencia
            </p>

            {{-- BIEN Y UBICACIÓN ACTUAL --}}
            <div class="p-4 bg-gray-50 border rounded-lg space-y-2">
                <p class="font-semibold">{{ $bienSeleccionado->descripcion }}</p>
                <p class="text-sm text-gray-600">{{ $bienSeleccionado->numero_inventario }}</p>
                
                @if($bienSeleccionado->dependencia)
                    <div class="mt-2 pt-2 border-t">
                        <p class="text-xs text-gray-500">Ubicación actual:</p>
                        <p class="text-sm font-medium text-gray-700">
                            {{ $bienSeleccionado->dependencia->nombre }}
                        </p>
                    </div>
                @endif
            </div>

            {{-- FORMULARIO --}}
            <div class="space-y-4">

                {{-- DEPENDENCIA DESTINO --}}
                <div>
                    <label class="block font-semibold text-sm mb-1">
                        Dependencia Destino*
                    </label>
                    <select wire:model.defer="dependencia_destino"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Seleccione dependencia</option>
                        @foreach($dependencias as $dep)
                            <option value="{{ $dep->id }}">
                                {{ $dep->codigo }} - {{ $dep->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('dependencia_destino')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- FECHA DE REASIGNACIÓN --}}
                <div>
                    <label class="block font-semibold text-sm mb-1">
                        Fecha de Reasignación*
                    </label>
                    <input type="date"
                           wire:model.defer="fecha_reasignacion"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('fecha_reasignacion')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- OBSERVACIONES --}}
                <div>
                    <label class="block font-semibold text-sm mb-1">Observaciones</label>
                    <textarea wire:model.defer="observaciones_reasignacion"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2"
                              rows="3"
                              placeholder="Motivo o detalles de la reasignación..."></textarea>
                    @error('observaciones_reasignacion')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- BOTONES --}}
            <div class="flex justify-end gap-2 mt-4">

                <button wire:click="$set('modalReasignacion', false)"
                        class="px-4 py-2 rounded-md border text-gray-700 hover:bg-gray-100">
                    Cancelar
                </button>

                <button wire:click="guardarReasignacion"
                        class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.8"
                         stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 7h13m0 0l-4-4m4 4l-4 4M20 17H7m0 0l4-4m-4 4l4 4" />
                    </svg>
                    Reasignar
                </button>

            </div>

        </div>
    </div>
</div>
@endif