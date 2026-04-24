<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dependencia extends Model {
    protected $table = 'dependencias';
    protected $fillable = ['codigo', 'nombre', 'ubicacion', 'responsable', 'telefono', 'activo'];
    
    public function bienes() {
        return $this->hasMany(Bien::class);
    }
    public function asignaciones() {
        return $this->hasMany(Asignacion::class);
    }
}