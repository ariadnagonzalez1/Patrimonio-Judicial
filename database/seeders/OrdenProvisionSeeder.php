<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\OrdenProvision;
class OrdenProvisionSeeder extends Seeder {
    public function run(): void {
        OrdenProvision::create([
            'numero_orden' => 'OP-2024-0090',
            'fecha_orden' => '2024-01-15',
            'numero_expediente' => 'EXP-2024-0001',
            'proveedor_id' => 1,
            'monto_autorizado' => 500000.00
        ]);
        OrdenProvision::create([
            'numero_orden' => 'OP-2024-0098',
            'fecha_orden' => '2024-02-01',
            'numero_expediente' => 'EXP-2024-0002',
            'proveedor_id' => 2,
            'monto_autorizado' => 300000.00
        ]);
    }
}