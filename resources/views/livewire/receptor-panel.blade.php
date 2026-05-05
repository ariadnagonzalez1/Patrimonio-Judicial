{{-- resources/views/livewire/receptor-panel.blade.php --}}
<div>
    <style>
        .hidden-message nav > div > div:first-child {
            display: none !important;
        }
    </style>

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Panel de Receptor</h1>
        <p class="text-gray-600 mt-1">Registro y alta de bienes patrimoniales</p>
    </div>

    <!-- Contenido Principal -->
    <div class="bg-gray-50 rounded-lg p-6">
        @if($mostrarRegistros)
            {{-- Usa el componente que ya tienes --}}
            @include('components.biennes-tablet') {{-- o como se llame tu tabla --}}
        @else
            {{-- Usa los componentes que ya tienes en components/ --}}
            <form wire:submit.prevent="registrarBien">
                <!-- Botón Ver Registros -->
                <div class="flex justify-end mb-4">
                    <button type="button" wire:click="verRegistros" 
                        class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V7.414A2 2 0 0017.414 6L14 2.586A2 2 0 0012.586 2H4z"/>
                        </svg>
                        <span>Ver Registros</span>
                    </button>
                </div>

                {{-- Datos del Remito --}}
               @include('components.remito-data') {{-- Nota: tu archivo se llama "remoto-data" con typo --}}

                {{-- Información del Bien --}}
                <div class="mb-6">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 shadow-sm space-y-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18m-9 5h9"/>
                                </svg>
                                <h3 class="text-base font-semibold text-gray-800">Información del Bien</h3>
                            </div>
                            <button type="button" wire:click="agregarFormulario"
                                class="flex items-center gap-1 px-3 py-1 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                <span>+ Agregar</span>
                            </button>
                        </div>

                        @foreach($formularios as $index => $form)
                            @include('components.form-item', ['form' => $form, 'index' => $index])
                        @endforeach
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex justify-end space-x-3">
                    <button type="button" wire:click="cancelar"
                        class="px-6 py-2 rounded-md bg-red-600 text-white font-medium hover:bg-red-700">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800 font-medium flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Registrar Bien</span>
                    </button>
                </div>
            </form>
        @endif
    </div>

    <!-- Modal de Edición -->
    @if($editandoBien)
        @include('components.edit-modal') {{-- Si existe --}}
    @endif

    <!-- Alertas -->
    @include('livewire.partials.alerts')

    <script>
        function confirmarEliminacion(bienId) {
            if (confirm('¿Estás segura de eliminar este bien? Esta acción no se puede deshacer.')) {
                Livewire.dispatch('eliminarBienConfirmado', { id: bienId });
            }
        }
    </script>
</div>