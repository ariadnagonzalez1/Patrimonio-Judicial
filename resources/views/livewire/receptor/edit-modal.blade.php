{{-- resources/views/livewire/receptor/edit-modal.blade.php --}}
@if($editandoBien)
<div class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    
    {{-- Contenedor principal del modal --}}
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden">
        
        {{-- Header - Fijo --}}
        <div class="px-6 py-4 border-b flex items-center justify-between bg-gray-50 flex-shrink-0">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Modificar Bien
            </h2>
            <button type="button" 
                    wire:click="$set('editandoBien', null)" 
                    class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body - Scrollable --}}
        <div class="flex-1 overflow-y-auto p-6">
            
            {{-- Datos Administrativos (solo lectura) --}}
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18m-9 5h9"/>
                    </svg>
                    Datos Administrativos (No editables)
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="text-xs text-gray-500 block">N° Remito</label>
                        <div class="mt-1 px-3 py-2 bg-gray-100 border border-gray-200 rounded-lg text-sm font-mono">
                            {{ $editandoBien['numero_remito'] ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block">N° Expediente</label>
                        <div class="mt-1 px-3 py-2 bg-gray-100 border border-gray-200 rounded-lg text-sm">
                            {{ $editandoBien['numero_expediente'] ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block">Orden de Provisión</label>
                        <div class="mt-1 px-3 py-2 bg-gray-100 border border-gray-200 rounded-lg text-sm">
                            {{ $editandoBien['orden_provision'] ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block">N° Inventario</label>
                        <div class="mt-1 px-3 py-2 bg-gray-100 border border-gray-200 rounded-lg text-sm font-mono">
                            {{ $editandoBien['numero_inventario'] ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Datos Editables del Bien --}}
            <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Datos del Bien (Editables)
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1 block">
                            Cuenta <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.defer="editandoBien.cuenta_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Seleccione cuenta</option>
                            @foreach($cuentas as $cuenta)
                                <option value="{{ $cuenta->id }}">{{ $cuenta->codigo }} - {{ $cuenta->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1 block">
                            Proveedor <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.defer="editandoBien.proveedor_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Seleccione proveedor</option>
                            @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->id }}">{{ $proveedor->razon_social }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-700 mb-1 block">
                            Descripción <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model.defer="editandoBien.descripcion"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1 block">
                            Fecha de Recepción <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model.defer="editandoBien.fecha_recepcion"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1 block">
                            Empleado que Recibe <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model.defer="editandoBien.empleado_recibe"
                               placeholder="Nombre del empleado"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </div>

            {{-- Datos Económicos (solo lectura) --}}
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Datos Económicos (No editables)
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs text-gray-500 block">Cantidad</label>
                        <div class="mt-1 px-3 py-2 bg-gray-100 border border-gray-200 rounded-lg text-sm">
                            {{ $editandoBien['cantidad'] ?? 0 }}
                        </div>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block">Precio Unitario</label>
                        <div class="mt-1 px-3 py-2 bg-gray-100 border border-gray-200 rounded-lg text-sm">
                            ${{ number_format($editandoBien['precio_unitario'] ?? 0, 2, ',', '.') }}
                        </div>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block">Monto Total</label>
                        <div class="mt-1 px-3 py-2 bg-gray-100 border border-gray-200 rounded-lg text-sm">
                            ${{ number_format($editandoBien['monto_total'] ?? 0, 2, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tipo de Bien y Tipo de Compra --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <label class="text-sm font-medium text-gray-700 mb-2 block">
                        Tipo de Bien <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center p-2 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" wire:model.defer="editandoBien.tipo_bien" value="uso"
                                   class="w-4 h-4 text-indigo-600">
                            <span class="ml-3">
                                <span class="text-sm font-medium">Bien de Uso</span>
                                <p class="text-xs text-gray-500">Perdura en el tiempo</p>
                            </span>
                        </label>
                        <label class="flex items-center p-2 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" wire:model.defer="editandoBien.tipo_bien" value="consumo"
                                   class="w-4 h-4 text-indigo-600">
                            <span class="ml-3">
                                <span class="text-sm font-medium">Bien de Consumo</span>
                                <p class="text-xs text-gray-500">Vida útil corta</p>
                            </span>
                        </label>
                    </div>
                </div>
                
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <label class="text-sm font-medium text-gray-700 mb-2 block">
                        Tipo de Compra
                    </label>
                    <label class="flex items-center p-2 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" wire:model.defer="editandoBien.compra_licitacion"
                               class="w-4 h-4 text-indigo-600 rounded">
                        <span class="ml-3">
                            <span class="text-sm font-medium">Compra por Licitación</span>
                            <p class="text-xs text-gray-500">Si no está marcado = Compra Directa</p>
                        </span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Footer - Fijo --}}
        <div class="px-6 py-4 border-t flex justify-end gap-3 bg-gray-50 flex-shrink-0">
            <button type="button" 
                    wire:click="$set('editandoBien', null)" 
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                Cancelar
            </button>
            <button type="button" 
                    wire:click="guardarEdicion" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Guardar Cambios
            </button>
        </div>
        
    </div>
</div>
@endif