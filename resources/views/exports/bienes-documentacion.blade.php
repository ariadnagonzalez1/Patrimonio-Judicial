<table>
    <thead>
        <tr style="background-color: #f3f4f6; font-weight: bold; text-align: center;">
            <th>N° Inventario</th>
            <th>Descripción</th>
            <th>Cuenta</th>
            <th>Expediente</th>
            <th>Orden de Provisión</th>
            <th>Remito</th>
            <th>Proveedor</th>
            <th>Fecha de Acta</th>
            <th>N° Acta</th>
            <th>N° Factura</th>
            <th>Fecha Factura</th>
            <th>Monto</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($documentaciones as $doc)
            <tr>
                <td>{{ $doc->bien->numero_inventario ?? '—' }}</td>
                <td>{{ $doc->bien->descripcion ?? '—' }}</td>
                <td>{{ $doc->bien->cuenta->codigo ?? '—' }}</td>
                <td>{{ $doc->bien->remito->numero_expediente ?? '—' }}</td>
                <td>{{ $doc->bien->remito->orden_provision ?? '—' }}</td>
                <td>{{ $doc->bien->remito->numero_remito ?? '—' }}</td>
                <td>{{ $doc->bien->proveedor->razon_social ?? '—' }}</td>
                <td>{{ $doc->fecha_acta ? \Carbon\Carbon::parse($doc->fecha_acta)->format('d/m/Y') : '—' }}</td>
                <td>{{ $doc->numero_acta ?? '—' }}</td>
                <td>{{ $doc->numero_factura ?? '—' }}</td>
                <td>{{ $doc->fecha_factura ? \Carbon\Carbon::parse($doc->fecha_factura)->format('d/m/Y') : '—' }}</td>
                <td>{{ $doc->monto ?? '—' }}</td>
                <td>{{ ucfirst($doc->estado ?? 'Pendiente') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
