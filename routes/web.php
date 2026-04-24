<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\AdminPanel;
use App\Livewire\ReceptorPanel;
use App\Livewire\GestorInventario;
use App\Livewire\DataentryPanel;
use App\Livewire\ConsultorPanel;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DependenciaController;
use App\Exports\BienesDocumentacionExport;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Redirección inicial inteligente
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        $rol = Auth::user()->rol?->nombre;

        return match ($rol) {
            'administrador'     => redirect()->route('admin.panel'),
            'recepcionista'     => redirect()->route('receptor.panel'),
            'gestorInventario'  => redirect()->route('gestor.panel'),
            'dataEntry'         => redirect()->route('dataentry.panel'),
            'consultor'         => redirect()->route('consultor.panel'),
            default             => redirect()->route('login'),
        };
    }

    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| RUTAS POR ROL (con acceso total para administrador)
|--------------------------------------------------------------------------
*/

// 🔹 Panel de ADMIN
Route::middleware(['auth', 'rol:administrador'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', AdminPanel::class)->name('panel');
    });

// 🔹 Panel del RECEPTOR
Route::middleware(['auth', 'rol:administrador,recepcionista'])
    ->prefix('receptor')
    ->name('receptor.')
    ->group(function () {
        Route::get('/', ReceptorPanel::class)->name('panel');
    });

// 🔹 Panel del GESTOR DE INVENTARIO
Route::middleware(['auth', 'rol:administrador,gestorInventario'])
    ->prefix('gestor')
    ->name('gestor.')
    ->group(function () {
        Route::get('/', GestorInventario::class)->name('panel');
    });

// 🔹 Panel del DATA ENTRY
Route::middleware(['auth', 'rol:administrador,dataEntry'])
    ->prefix('dataentry')
    ->name('dataentry.')
    ->group(function () {
        Route::get('/', DataentryPanel::class)->name('panel');
    });

// 🔹 Panel del CONSULTOR
Route::middleware(['auth', 'rol:administrador,consultor'])
    ->prefix('consultor')
    ->name('consultor.')
    ->group(function () {
        Route::get('/', ConsultorPanel::class)->name('panel');
    });

/*
|--------------------------------------------------------------------------
| Perfil del usuario autenticado
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Dependencias (importación y listado)
|--------------------------------------------------------------------------
*/
Route::get('/dependencias', [DependenciaController::class, 'index'])->name('dependencias.index');
Route::get('/dependencias/importar', [DependenciaController::class, 'mostrarImportador'])->name('dependencias.importar.form');
Route::post('/dependencias/importar', [DependenciaController::class, 'procesarImportacion'])->name('dependencias.importar.procesar');

/*
|--------------------------------------------------------------------------
| Exportación de Excel (Documentación de bienes)
|--------------------------------------------------------------------------
*/
Route::get('/exportar-excel/{inicio}/{fin}', function ($inicio, $fin) {
    $nombreArchivo = "bienes_documentacion_{$inicio}_a_{$fin}.xlsx";
    return Excel::download(new BienesDocumentacionExport($inicio, $fin), $nombreArchivo);
})->name('exportar.excel');

/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
