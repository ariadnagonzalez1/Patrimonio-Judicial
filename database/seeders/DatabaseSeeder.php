<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            RolSeeder::class,
            UsuarioSeeder::class,
            CuentaSeeder::class,
            DependenciaSeeder::class,
            ProveedorSeeder::class,
            OrdenProvisionSeeder::class,
            RemitoSeeder::class,
        ]);
    }
}