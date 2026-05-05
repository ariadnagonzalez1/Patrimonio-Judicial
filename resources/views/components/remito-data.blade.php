{{-- resources/views/components/remito-data.blade.php --}}
<div style="margin-bottom: 24px;">
    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px;">
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 16px; color: #1f2937;">Datos del Remito</h3>
        
        <div style="display: flex; flex-wrap: wrap; gap: 16px;">
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 4px;">
                    N° de Remito <span style="color: red;">*</span>
                </label>
                <input type="text" wire:model.live="numero_remito" 
                    placeholder="Ingrese número de remito"
                    style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                @if($alerta_remito)
                    <p style="color: red; font-size: 12px; margin-top: 4px;">{{ $alerta_remito }}</p>
                @endif
            </div>

            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 4px;">
                    N° de Expediente <span style="color: red;">*</span>
                </label>
                <input type="text" wire:model.live="numero_expediente" 
                    placeholder="Ingrese número de expediente"
                    style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                @if($alerta_expediente)
                    <p style="color: red; font-size: 12px; margin-top: 4px;">{{ $alerta_expediente }}</p>
                @endif
            </div>

            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 14px; font-weight: 500; margin-bottom: 4px;">
                    Orden de Provisión <span style="color: red;">*</span>
                </label>
                <input type="text" wire:model.live="orden_provision" 
                    placeholder="Ingrese orden de provisión"
                    style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                @if($alerta_orden)
                    <p style="color: red; font-size: 12px; margin-top: 4px;">{{ $alerta_orden }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.remito-container {
    margin-bottom: 1.5rem;
}

.remito-card {
    background-color: white;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1.5rem;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.remito-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 1rem;
}

.remito-grid {
    display: grid;
    grid-template-columns: repeat(1, minmax(0, 1fr));
    gap: 1rem;
}

@media (min-width: 768px) {
    .remito-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

.remito-field {
    display: flex;
    flex-direction: column;
}

.remito-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.25rem;
}

.remito-input {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    transition: all 0.2s;
}

.remito-input:focus {
    outline: none;
    border-color: #6366f1;
    ring: 2px solid #6366f1;
}

.remito-error {
    font-size: 0.75rem;
    color: #dc2626;
    margin-top: 0.25rem;
}

.text-red-500 {
    color: #ef4444;
}
</style>