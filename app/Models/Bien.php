<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bien extends Model
{
    protected $table = 'bienes';

    protected $fillable = [
        'lote_padre_id', 'es_lote', 'numero_inventario', 'descripcion', 'cantidad',
        'precio_unitario', 'monto_total', 'bien_uso', 'bien_consumo', 'estado',
        'fecha_baja', 'causa_baja', 'cuenta_id', 'remito_id', 'dependencia_id',
        'proveedor_id', 'codigo_qr', 'foto', 'foto_remito', 'empleado_recibe',
    ];

    protected $casts = [
        'fecha_baja'      => 'date',
        'es_lote'         => 'boolean',
        'bien_uso'        => 'boolean',
        'bien_consumo'    => 'boolean',
        'precio_unitario' => 'decimal:2',
        'monto_total'     => 'decimal:2',
    ];

    // Carga automática de relaciones siempre necesarias
    protected $with = ['cuenta', 'proveedor', 'remito'];

    // ===== RELACIONES =====
    public function lotePadre()   { return $this->belongsTo(Bien::class, 'lote_padre_id'); }
    public function bienesHijos() { return $this->hasMany(Bien::class, 'lote_padre_id'); }
    public function cuenta()      { return $this->belongsTo(Cuenta::class); }
    public function proveedor()   { return $this->belongsTo(Proveedor::class); }
    public function remito()      { return $this->belongsTo(Remito::class, 'remito_id'); }
    public function asignaciones(){ return $this->hasMany(Asignacion::class); }
    public function documentacion(){ return $this->hasOne(Documentacion::class, 'bien_id'); }
    public function dependencia() { return $this->belongsTo(Dependencia::class, 'dependencia_id'); }

    // ===== SCOPES =====
    public function scopeDeUso($query)       { return $query->where('bien_uso', true); }
    public function scopeDeConsumo($query)   { return $query->where('bien_consumo', true); }
    public function scopeEnStock($query)     { return $query->where('estado', 'stock'); }
    public function scopeAsignados($query)   { return $query->where('estado', 'asignado'); }
    public function scopeDeBaja($query)      { return $query->where('estado', 'baja'); }
    public function scopeLotes($query)       { return $query->where('es_lote', true); }
    public function scopeIndividuales($query){ return $query->where('es_lote', false); }
}