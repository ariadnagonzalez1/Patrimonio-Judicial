<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    protected $table = 'asignaciones';

    protected $fillable = [
        'bien_id',
        'dependencia_id',
        'fecha_asignacion',
        'user_id',
        'recibido_por',
        'observacion',
    ];

    protected $dates = ['fecha_asignacion'];

    // 🔹 Relación con el bien
    public function bien()
    {
        return $this->belongsTo(Bien::class);
    }

    // 🔹 Relación con la dependencia
    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class);
    }

    // 🔹 Usuario que realizó la asignación
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 🔹 Usuario que recibió el bien (si aplica)
    public function recibidoPor()
    {
        return $this->belongsTo(User::class, 'recibido_por');
    }
}
