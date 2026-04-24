<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Dependencia;
class DependenciaSeeder extends Seeder {
    public function run(): void {
        Dependencia::create([
            'codigo' => 'JUZ1CIV',
            'nombre' => 'Juzgado 1º Civil',
            'ubicacion' => 'Planta Baja',
            'responsable' => 'Dr. Roberto Sánchez',
            'telefono' => '3704-5001001'
        ]);
        Dependencia::create([
            'codigo' => 'JUZ2CIV',
            'nombre' => 'Juzgado 2º Civil',
            'ubicacion' => 'Primer Piso',
            'responsable' => 'Dra. Laura Fernández',
            'telefono' => '3704-5001002'
        ]);
        Dependencia::create([
            'codigo' => 'MESA',
            'nombre' => 'Mesa de Entradas',
            'ubicacion' => 'Planta Baja',
            'responsable' => 'Sra. Carmen Ruiz',
            'telefono' => '3704-5001003'
        ]);
        Dependencia::create([
            'codigo' => 'SECADM',
            'nombre' => 'Secretaría Administrativa',
            'ubicacion' => 'Segundo Piso',
            'responsable' => 'Lic. Diego Torres',
            'telefono' => '3704-5001004'
        ]);
    }
}
