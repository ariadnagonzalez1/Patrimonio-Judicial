<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Proveedor;
class ProveedorSeeder extends Seeder {
    public function run(): void {
        Proveedor::create([
            'razon_social' => 'Muebles Oficina SA',
            'cuil' => '30-12345678-9',
            'nombre_contacto' => 'Carlos Rodríguez',
            'telefono' => '011-4567-8900',
            'email' => 'ventas@mueblesoficina.com'
        ]);
        Proveedor::create([
            'razon_social' => 'TechnoComp SRL',
            'cuil' => '30-98765432-1',
            'nombre_contacto' => 'María López',
            'telefono' => '011-4321-0987',
            'email' => 'contacto@technocomp.com'
        ]);
        Proveedor::create([
            'razon_social' => 'Papelería Central',
            'cuil' => '30-55544433-2',
            'nombre_contacto' => 'Roberto García',
            'telefono' => '011-5555-1234',
            'email' => 'info@papeleriacentral.com'
        ]);
    }
}