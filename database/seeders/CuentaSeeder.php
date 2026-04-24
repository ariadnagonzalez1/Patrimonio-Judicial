<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Cuenta;
class CuentaSeeder extends Seeder {
    public function run(): void {
        Cuenta::create(['codigo' => '400', 'descripcion' => 'MOBILIARIO', 'tipo' => 'uso']);
        Cuenta::create(['codigo' => '451', 'descripcion' => 'EQUIPOS DE TECNOLOGÍA', 'tipo' => 'uso']);
        Cuenta::create(['codigo' => '423', 'descripcion' => 'EQUIPOS ELECTRÓNICOS', 'tipo' => 'uso']);
        Cuenta::create(['codigo' => '312', 'descripcion' => 'MATERIALES DE OFICINA', 'tipo' => 'consumo']);
    }
}
