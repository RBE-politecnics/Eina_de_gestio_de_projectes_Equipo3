<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tasques extends Model
{
    protected $fillable = [
        'projecte_id',
        'users_id',
        'nom',
        'data_finalitzacio',
        'estats_id',
        'comentaris',
        'progres',
        'color',
    ];
}
