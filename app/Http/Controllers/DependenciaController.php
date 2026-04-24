<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\DependenciasImport;
use App\Models\Dependencia;
use Maatwebsite\Excel\Facades\Excel;

class DependenciaController extends Controller
{
    /**
     * Mostrar la lista de dependencias
     */
    public function index()
    {
        $dependencias = Dependencia::orderBy('nombre')->paginate(15);
        return view('dependencias.index', compact('dependencias'));
    }

    /**
     * Mostrar formulario para importar archivo Excel
     */
    public function mostrarImportador()
    {
        return view('dependencias.importar');
    }

    /**
     * Procesar archivo subido e importar dependencias (Hoja 1)
     */
    public function procesarImportacion(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xls,xlsx',
        ]);

        Excel::import(new DependenciasImport, $request->file('archivo'));

        return redirect()->route('dependencias.index')
            ->with('success', '✅ Dependencias de la Hoja 1 importadas correctamente.');
    }

    /**
     * (Opcional) Método anterior para importar desde storage, por consola
     */
    public function importar()
    {
        Excel::import(
            new DependenciasImport,
            storage_path('app/public/LISTADO DE CODIGOS POR DEPENDENCIAS.xls')
        );

        return response('✅ Dependencias de la Hoja 1 importadas correctamente.');
    }
}
