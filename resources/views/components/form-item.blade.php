{{-- resources/views/components/form-item.blade.php --}}
@props(['form', 'index'])

<div style="position: relative; margin-bottom: 24px;" wire:key="form-{{ $form['id'] }}">

    {{-- Botón eliminar bien (solo si hay más de uno) --}}
    @if(count($formularios) > 1)
        <button type="button" wire:click="eliminarFormulario({{ $form['id'] }})"
            style="position: absolute; top: -12px; right: 0; z-index: 10; display: flex; align-items: center; gap: 4px; padding: 4px 12px; font-size: 12px; background-color: #ef4444; color: white; border-radius: 6px; border: none; cursor: pointer;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-9 0h10"/>
            </svg>
            <span>Eliminar Bien</span>
        </button>
    @endif

    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px;">

        {{-- PRIMERA FILA: Cuenta (con buscador), Inventario, Cantidad --}}
        <div style="display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 20px;">

            {{-- ──────────────────────────────────────────────────────────────
                 CUENTA DEL BIEN — buscador Alpine.js
                 FIX: options como getter que lee data-options (atributo HTML)
                 así Livewire lo actualiza en cada re-render y Alpine no queda
                 con datos viejos en memoria tras limpiar el formulario.
            ────────────────────────────────────────────────────────────── --}}
            <div style="flex: 1; min-width: 200px; position: relative;"
                 x-data="{
                     search: '',
                     open: false,
                     selectedId: {{ $form['cuenta_id'] ?: 'null' }},
                     get options() {
                         return JSON.parse(this.$el.dataset.options);
                     },
                     init() {
                         let selected = this.options.find(opt => opt.id == this.selectedId);
                         if (selected) this.search = selected.text;
                     },
                     get filteredOptions() {
                         if (!this.search) return this.options;
                         return this.options.filter(opt =>
                             opt.text.toLowerCase().includes(this.search.toLowerCase())
                         );
                     },
                     selectOption(id) {
                         this.selectedId = id;
                         this.open = false;
                         let selected = this.options.find(opt => opt.id == id);
                         if (selected) this.search = selected.text;
                         $wire.set('formularios.{{ $index }}.cuenta_id', id, false);
                     }
                 }"
                 data-options="{{ json_encode($cuentas->map(fn($c) => ['id' => $c->id, 'text' => $c->codigo . ' - ' . $c->descripcion])->toArray()) }}">

                <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 4px;">
                    Cuenta del Bien <span style="color: red;">*</span>
                </label>

                <input type="text"
                       x-model="search"
                       @focus="open = true"
                       @click="search = ''; open = true"
                       @click.away="open = false"
                       placeholder="Buscar cuenta..."
                       style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px;">

                <div x-show="open && filteredOptions.length > 0"
                     x-cloak
                     style="position: absolute; z-index: 9999; width: 100%; margin-top: 4px; background: white; border: 1px solid #d1d5db; border-radius: 8px; max-height: 200px; overflow-y: auto; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                    <template x-for="opt in filteredOptions" :key="opt.id">
                        <div @mousedown.prevent="selectOption(opt.id)"
                             style="padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #e5e7eb;"
                             @mouseenter="$el.style.backgroundColor='#f3f4f6'"
                             @mouseleave="$el.style.backgroundColor='white'">
                            <span x-text="opt.text" style="font-size: 14px;"></span>
                        </div>
                    </template>
                </div>
            </div>

            {{-- ──────────────────────────────────────────────────────────────
                 N° INVENTARIO
                 FIX: wire:model.blur en lugar de .live
                 Antes disparaba una request al servidor por cada tecla.
                 Con .blur solo valida al salir del campo.
            ────────────────────────────────────────────────────────────── --}}
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 4px;">
                    N° Inventario <span style="color: red;">*</span>
                </label>
                <input type="text"
                       wire:model.blur="formularios.{{ $index }}.numero_inventario"
                       placeholder="Ej: 9743"
                       style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                @if(isset($alertas_inventario[$index]))
                    <p style="color: red; font-size: 12px; margin-top: 4px;">{{ $alertas_inventario[$index] }}</p>
                @endif
            </div>

            {{-- ──────────────────────────────────────────────────────────────
                 CANTIDAD
                 FIX: wire:model.blur en lugar de .live
                 Recalcula monto_total solo al salir del campo, no en cada tecla.
            ────────────────────────────────────────────────────────────── --}}
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 4px;">
                    Cantidad <span style="color: red;">*</span>
                </label>
                <input type="number"
                       wire:model.blur="formularios.{{ $index }}.cantidad"
                       min="1"
                       style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>
        </div>

        {{-- DESCRIPCIÓN --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 4px;">
                Descripción del Bien <span style="color: red;">*</span>
            </label>
            <textarea wire:model.blur="formularios.{{ $index }}.descripcion"
                      rows="3"
                      placeholder="Descripción detallada del bien"
                      style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px;"></textarea>
        </div>

        {{-- SEGUNDA FILA: Precio Unitario, Monto Total, Fecha Recepción --}}
        <div style="display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 20px;">

            {{-- ──────────────────────────────────────────────────────────────
                 PRECIO UNITARIO
                 FIX: wire:model.blur — igual que cantidad, recalcula al salir
            ────────────────────────────────────────────────────────────── --}}
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 4px;">
                    Precio Unitario <span style="color: red;">*</span>
                </label>
                <input type="number"
                       wire:model.blur="formularios.{{ $index }}.precio_unitario"
                       step="0.01" min="0" placeholder="0.00"
                       style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>

            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 4px;">
                    Monto Total
                </label>
                <input type="number"
                       wire:model="formularios.{{ $index }}.monto_total"
                       readonly
                       style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px; background-color: #f9fafb;">
            </div>

            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 4px;">
                    Fecha de Recepción <span style="color: red;">*</span>
                </label>
                <input type="date"
                       wire:model.blur="formularios.{{ $index }}.fecha_recepcion"
                       style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>
        </div>

        {{-- TERCERA FILA: Proveedor, Empleado, Foto del Remito --}}
        <div style="display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 20px;">

            {{-- ──────────────────────────────────────────────────────────────
                 PROVEEDOR
                 FIX: wire:change con $set explícito + :selected en cada option
                 El problema original era que wire:model sin :selected perdía
                 el valor al re-renderizar. Con wire:change + selected explícito
                 el valor siempre queda correctamente marcado.
            ────────────────────────────────────────────────────────────── --}}
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 4px;">
                    Proveedor <span style="color: red;">*</span>
                </label>
                <select wire:change="$set('formularios.{{ $index }}.proveedor_id', $event.target.value)"
                        style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px; background: white;">
                    <option value="">-- Seleccionar proveedor --</option>
                    @foreach($proveedores as $p)
                        <option value="{{ $p->id }}" {{ $form['proveedor_id'] == $p->id ? 'selected' : '' }}>
                            {{ $p->razon_social }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- EMPLEADO QUE RECIBE --}}
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 4px;">
                    Empleado que Recibe el Bien <span style="color: red;">*</span>
                </label>
                <input type="text"
                       wire:model.blur="formularios.{{ $index }}.empleado_recibe"
                       placeholder="Nombre del empleado que recibe"
                       style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>

            {{-- FOTO DEL REMITO --}}
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 4px;">
                    Foto del Remito
                </label>
                <input type="file"
                       wire:model="fotos_remito.{{ $form['id'] }}"
                       accept="image/*"
                       style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px;">

                <div wire:loading wire:target="fotos_remito.{{ $form['id'] }}"
                     style="font-size: 12px; color: #6b7280; margin-top: 4px;">
                    ⏳ Subiendo imagen...
                </div>

                @if(isset($fotos_remito[$form['id']]) && $fotos_remito[$form['id']])
                    <div style="margin-top: 8px;">
                        <img src="{{ $fotos_remito[$form['id']]->temporaryUrl() }}"
                             style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid #d1d5db;">
                        <p style="font-size: 10px; color: #059669; margin-top: 4px;">
                            ✅ {{ $fotos_remito[$form['id']]->getClientOriginalName() }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- CUARTA FILA: Tipo de Bien y Tipo de Compra --}}
        <div style="display: flex; flex-wrap: wrap; gap: 32px;">

            {{-- TIPO DE BIEN --}}
            <div style="flex: 1; min-width: 250px;">
                <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 8px;">
                    Tipo de Bien <span style="color: red;">*</span>
                </label>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="radio"
                               wire:model="formularios.{{ $index }}.tipo_bien"
                               value="uso"
                               style="width: 16px; height: 16px; margin-right: 8px;">
                        <span style="font-size: 14px;">Bien de Uso (Perdura en el Tiempo)</span>
                    </label>
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="radio"
                               wire:model="formularios.{{ $index }}.tipo_bien"
                               value="consumo"
                               style="width: 16px; height: 16px; margin-right: 8px;">
                        <span style="font-size: 14px;">Bien de Consumo (Vida útil corta)</span>
                    </label>
                </div>
            </div>

            {{-- TIPO DE COMPRA --}}
            <div style="flex: 1; min-width: 250px;">
                <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 8px;">
                    Tipo de Compra
                </label>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox"
                               wire:model="formularios.{{ $index }}.compra_licitacion"
                               style="width: 16px; height: 16px; margin-right: 8px;">
                        <span style="font-size: 14px;">Compra por Licitación</span>
                    </label>
                    <p style="font-size: 12px; color: #6b7280; margin: 4px 0 0 24px;">
                        Si no está marcado = Compra Directa
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>