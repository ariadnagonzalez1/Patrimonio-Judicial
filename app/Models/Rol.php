<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model {
    protected $table = 'roles';
    protected $guarded = [];
    public function usuarios() {
        return $this->hasMany(User::class, 'role_id');
    }
}