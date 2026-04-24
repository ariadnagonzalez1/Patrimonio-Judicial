<?php

namespace App\Http\Controllers;

use App\Imports\CuentasImport;
use Maatwebsite\Excel\Facades\Excel;

class CuentaController extends Controller
{
    public function importar()
    {
        Excel::import(new CuentasImport, storage_path('app/public/CUENTAS.xlsx'));

        return response('✅ Cuentas importadas correctamente desde el archivo CUENTAS.xlsx.');
    }
}
