@if ($modalBaja && $bienSeleccionado)
<div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-6">
    <div class="bg-white w-full max-w-xl rounded-xl shadow-xl relative overflow-hidden">

        {{-- BOTÓN CERRAR --}}
        <button wire:click="$set('modalBaja', false)"
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
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.8"
                         stroke="currentColor" class="w-6 h-6 text-red-600">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Dar de Baja Bien</h2>
                    <p class="text-sm text-gray-600">Esta acción marcará el bien como dado de baja</p>
                </div>
            </div>

            {{-- BIEN --}}
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg space-y-2">
                <p class="font-semibold text-gray-900">{{ $bienSeleccionado->descripcion }}</p>
                <p class="text-sm text-gray-600">{{ $bienSeleccionado->numero_inventario }}</p>
            </div>

            {{-- FORMULARIO --}}
            <div class="space-y-4">

                {{-- MOTIVO DE BAJA --}}
                <div>
                    <label class="block font-semibold text-sm mb-1">
                        Motivo de Baja*
                    </label>
                    <select wire:model.defer="motivo_baja"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        <option value="">Seleccione motivo</option>
                        <option value="Deterioro por uso">Deterioro por uso</option>
                        <option value="Obsolescencia técnica">Obsolescencia técnica</option>
                        <option value="Rotura irreparable">Rotura irreparable</option>
                        <option value="Extravío">Extravío</option>
                        <option value="Robo">Robo</option>
                        <option value="Siniestro">Siniestro</option>
                        <option value="Donación">Donación</option>
                        <option value="Venta">Venta</option>
                        <option value="Otro">Otro</option>
                    </select>
                    @error('motivo_baja')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- FECHA DE BAJA --}}
                <div>
                    <label class="block font-semibold text-sm mb-1">
                        Fecha de Baja*
                    </label>
                    <input type="date"
                           wire:model.defer="fecha_baja"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                    @error('fecha_baja')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- DETALLES --}}
                <div>
                    <label class="block font-semibold text-sm mb-1">Detalles*</label>
                    <textarea wire:model.defer="detalles_baja"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 p-2"
                              rows="3"
                              placeholder="Describa los detalles de la baja..."></textarea>
                    @error('detalles_baja')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- ADVERTENCIA --}}
            <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                    <strong>Advertencia:</strong> Esta acción cambiará el estado del bien a "Baja". 
                    El bien no se eliminará del sistema y podrá consultarse en el histórico.
                </p>
            </div>

            {{-- BOTONES --}}
            <div class="flex justify-end gap-2 mt-4">

                <button wire:click="$set('modalBaja', false)"
                        class="px-4 py-2 rounded-md border text-gray-700 hover:bg-gray-100">
                    Cancelar
                </button>

                <button wire:click="confirmarBaja"
                        class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.8"
                         stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Confirmar Baja
                </button>

            </div>

        </div>
    </div>
</div>
@endif