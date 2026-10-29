<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projecte_Usuari extends Model
{
    protected $table = 'projecte__usuaris';

    protected $fillable = [
        'projecte_id',
        'users_id',
        'rols_id',
    ];
}
