<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    protected $table = "users";
    
    protected $fillable = [
        'nombres',
        'apellidos',
        'telefono',
        'email',
        'email_verified_at',
        'fecha_registro',
        'fecha_ultima_modificacion',
    ];
}
