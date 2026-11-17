<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    protected function casts(): array
    {
        return [
            'data_finalitzacio' => 'date',
            'progres' => 'integer',
        ];
    }

    public function projecte(): BelongsTo
    {
        return $this->belongsTo(Projects::class, 'projecte_id');
    }

    public function usuari(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function estat(): BelongsTo
    {
        return $this->belongsTo(Estats::class, 'estats_id');
    }
}
