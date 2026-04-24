<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Remito extends Model {
    protected $table = 'remitos';
    protected $fillable = [
    'numero_remito',
    'numero_expediente',
    'orden_provision',
    'fecha_recepcion',
    'foto_remito',
    'tipo_compra',
    'proveedor_id',
    'user_id',
];
    protected $dates = ['fecha_recepcion'];
    
    public function proveedor() {
        return $this->belongsTo(Proveedor::class);
    }
    public function usuario() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function ordenProvision() {
        return null;
    }
    public function bienes()
{
    return $this->hasMany(Bien::class, 'remito_id');
}
    
}