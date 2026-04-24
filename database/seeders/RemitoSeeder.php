<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Remito;
class RemitoSeeder extends Seeder {
    public function run(): void {
        Remito::create([
            'numero_remito'     => 'REM-2024-0312',
            'numero_expediente' => 'EXP-2024-0055',  
            'orden_provision'   => 'OP-2024-0001', 
            'fecha_recepcion'   => '2024-01-20',
            'tipo_compra'       => 'directa',
            'proveedor_id'      => 1,
            'user_id'           => 2,
]);

        Remito::create([
            'numero_remito'     => 'REM-2024-612',
            'numero_expediente' => 'EXP-2024-0665',  
            'orden_provision'   => 'OP-2024-0002', 
            'fecha_recepcion'   => '2024-01-20',
            'tipo_compra'       => 'directa',
            'proveedor_id'      => 1,
            'user_id'           => 2,
        ]);
    }
}
