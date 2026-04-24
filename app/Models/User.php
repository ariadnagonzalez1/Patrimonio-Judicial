<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;
    protected $fillable = ['nombre', 'email', 'password', 'role_id', 'activo'];
    protected $hidden = ['password', 'remember_token'];
    
    public function rol() {
        return $this->belongsTo(Rol::class, 'role_id');
    }
    public function asignaciones() {
        return $this->hasMany(Asignacion::class);
    }
    public function remitos() {
        return $this->hasMany(Remito::class);
    }
    public function recibimientos() {
        return $this->hasMany(Asignacion::class, 'recibido_por');
    }
}