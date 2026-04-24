<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model {
    protected $table = 'proveedores';
    protected $fillable = ['razon_social', 'cuil', 'nombre_contacto', 'telefono', 'email', 'direccion', 'estado'];
    
    public function bienes() {
        return $this->hasMany(Bien::class);
    }
    public function ordenes() {
        return $this->hasMany(OrdenProvision::class);
    }
    public function remitos() {
        return $this->hasMany(Remito::class);
    }
    public function documentaciones() {
        return $this->hasMany(Documentacion::class);
    }
}
