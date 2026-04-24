<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documentacion extends Model
{
    use HasFactory;

    protected $table = 'documentaciones';

    protected $fillable = [
        'bien_id',
        'numero_acta',
        'fecha_acta',
        'numero_resolucion',
        'numero_factura',
        'fecha_factura',
        'monto',
        'partida_presupuestaria',
        'orden_pago',
        'estado',
        'observaciones',
    ];

    // Relaciones
    public function bien()
{
    return $this->belongsTo(Bien::class, 'bien_id');
}
}
