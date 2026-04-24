<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenProvision extends Model {
    protected $table = 'ordenes_provision';
    protected $fillable = ['numero_orden', 'fecha_orden', 'numero_expediente', 'proveedor_id', 'monto_autorizado', 'observaciones'];
    
    public function proveedor() {
        return $this->belongsTo(Proveedor::class);
    }
    public function remitos() {
        return $this->hasMany(Remito::class);
    }
    public function documentaciones() {
        return $this->hasMany(Documentacion::class);
    }
}