<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Route;

#[Layout('components.admin-layout', ['title' => 'Panel de Administración'])]
class AdminPanel extends Component
{
    public $activeTab = 'usuarios'; // pestaña activa por defecto

    // 🔹 Lista de paneles a los que puede acceder el admin
    public $paneles = [
        [
            'nombre' => 'Panel del Receptor',
            'descripcion' => 'Gestión de recepción y carga de bienes nuevos.',
            'ruta' => 'receptor.panel',
            'icono' => '📦',
        ],
        [
            'nombre' => 'Panel del Gestor de Inventario',
            'descripcion' => 'Asignación de bienes y control de inventario.',
            'ruta' => 'gestor.panel',
            'icono' => '🗂️',
        ],
        [
            'nombre' => 'Panel de Data Entry',
            'descripcion' => 'Carga de documentación y actualización de bienes.',
            'ruta' => 'dataentry.panel',
            'icono' => '📝',
        ],
        [
            'nombre' => 'Panel del Consultor',
            'descripcion' => 'Visualización completa de bienes y asignaciones.',
            'ruta' => 'consultor.panel',
            'icono' => '🔍',
        ],
    ];

    public function redirigir($ruta)
    {
        return redirect()->route($ruta);
    }

    public function render()
    {
        return view('livewire.admin-panel');
    }
}
