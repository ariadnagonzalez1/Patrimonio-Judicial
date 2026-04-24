<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UsuarioSeeder extends Seeder {
    public function run(): void {
        User::create([
            'nombre' => 'Admin Principal',
            'email' => 'admin@judicial.gob.ar',
            'password' => Hash::make('password123'),
            'role_id' => 1,
            'activo' => true
        ]);
        User::create([
            'nombre' => 'Juan Pérez Receptor',
            'email' => 'recepcionista@judicial.gob.ar',
            'password' => Hash::make('password123'),
            'role_id' => 2,
            'activo' => true
        ]);
        User::create([
            'nombre' => 'María García Cargador',
            'email' => 'dataEntry@judicial.gob.ar',
            'password' => Hash::make('password123'),
            'role_id' => 3,
            'activo' => true
        ]);
        User::create([
            'nombre' => 'Carlos López Repartidor',
            'email' => 'gestorinventario@judicial.gob.ar',
            'password' => Hash::make('password123'),
            'role_id' => 4,
            'activo' => true
        ]);
        User::create([
            'nombre' => 'Ana Martínez Consultor',
            'email' => 'consultor@judicial.gob.ar',
            'password' => Hash::make('password123'),
            'role_id' => 5,
            'activo' => true
        ]);
    }
}
