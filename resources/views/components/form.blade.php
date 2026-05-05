{{-- resources/views/livewire/receptor/components/form.blade.php --}}
<div>
    <form wire:submit.prevent="registrarBien">
        <!-- Botón Ver Registros -->
        <div class="flex justify-end mb-4">
            <button type="button" 
                    wire:click="verRegistros"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V7.414A2 2 0 0017.414 6L14 2.586A2 2 0 0012.586 2H4z"/>
                </svg>
                <span>Ver Registros</span>
            </button>
        </div>

        <!-- Datos del Remito -->
        @include('livewire.receptor.components.remito-data')

        <!-- Información del Bien -->
        <div class="mb-6">
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 shadow-sm space-y-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18m-9 5h9"/>
                        </svg>
                        <h3 class="text-base font-semibold text-gray-800">Información del Bien</h3>
                    </div>
                    <button type="button"
                            wire:click="agregarFormulario"
                            class="flex items-center gap-1 px-3 py-1 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                        <span>+ Agregar</span>
                    </button>
                </div>

                @foreach($formularios as $index => $form)
                    @include('livewire.receptor.components.form-item', [
                        'form' => $form, 
                        'index' => $index,
                        'cuentas' => $cuentas,
                        'proveedores' => $proveedores
                    ])
                @endforeach
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="flex justify-end space-x-3">
            <button type="button"
                    wire:click="cancelar"
                    class="px-6 py-2 rounded-md bg-red-600 text-white font-medium hover:bg-red-700 transition-colors">
                Cancelar
            </button>
            <button type="submit"
                    class="px-6 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800 font-medium transition-colors flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Registrar Bien</span>
            </button>
        </div>
    </form>
</div>