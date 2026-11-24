<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipusTasca extends Model
{
    protected $table = 'tipus_tasca';

    public $timestamps = false;

    protected $fillable = [
        'nom',
        'color',
    ];
}
