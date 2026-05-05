{{-- resources/views/livewire/receptor/components/bien-row.blade.php --}}
@props(['bien', 'index'])

<tr class="hover:bg-gray-50">
    <td class="px-4 py-2">{{ $index + 1 }}</td>
    <td class="px-4 py-2">{{ $bien->remito->numero_expediente ?? '-' }}</td>
    <td class="px-4 py-2">{{ $bien->remito->orden_provision ?? '-' }}</td>
    <td class="px-4 py-2">{{ $bien->remito->numero_remito ?? '-' }}</td>
    <td class="px-4 py-2">{{ $bien->cuenta->codigo ?? '-' }}</td>
    <td class="px-4 py-2 font-mono">{{ $bien->numero_inventario ?? '-' }}</td>
    <td class="px-4 py-2 max-w-xs truncate" title="{{ $bien->descripcion ?? '' }}">
        {{ Str::limit($bien->descripcion ?? '-', 50) }}
    </td>
    <td class="px-4 py-2 text-center">{{ $bien->cantidad }}</td>
    <td class="px-4 py-2 text-right">${{ number_format($bien->precio_unitario, 2, ',', '.') }}</td>
    <td class="px-4 py-2 text-right">${{ number_format($bien->monto_total, 2, ',', '.') }}</td>
    <td class="px-4 py-2">{{ Str::limit($bien->proveedor->razon_social ?? '-', 30) }}</td>
    <td class="px-4 py-2 text-center">
        {{ optional($bien->remito)->fecha_recepcion?->format('d/m/Y') ?? '-' }}
    </td>
    <td class="px-4 py-2 text-center">
        @if ($bien->foto)
            <a href="{{ asset('storage/' . $bien->foto) }}" target="_blank">
                <img src="{{ asset('storage/' . $bien->foto) }}" 
                     alt="Foto" 
                     class="w-12 h-12 object-cover rounded-md border shadow-sm hover:scale-105 transition">
            </a>
        @else
            <span class="text-gray-400 text-xs">Sin foto</span>
        @endif
    </td>
    <td class="px-4 py-2 text-center">
        <div class="flex gap-2 items-center justify-center">
            <button type="button" 
                    wire:click="editarBien({{ $bien->id }})"
                    class="px-3 py-1 bg-amber-500 text-white rounded-md text-xs hover:bg-amber-600">
                Editar
            </button>
            <button type="button" 
                    onclick="confirmarEliminacion({{ $bien->id }})"
                    class="px-3 py-1 bg-red-600 text-white rounded-md text-xs hover:bg-red-700">
                Eliminar
            </button>
        </div>
    </td>
</tr>