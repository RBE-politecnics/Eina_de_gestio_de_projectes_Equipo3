<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait ComprovaPermisos
{
    protected function esMembre(int $projecteId): bool
    {
        return DB::table('projecte__usuaris')
            ->where('projecte_id', $projecteId)
            ->where('users_id', Auth::id())
            ->exists();
    }

    protected function esAdmin(int $projecteId): bool
    {
        return DB::table('projecte__usuaris')
            ->join('rols', 'rols.id', '=', 'projecte__usuaris.rols_id')
            ->where('projecte__usuaris.projecte_id', $projecteId)
            ->where('projecte__usuaris.users_id', Auth::id())
            ->where('rols.nom', 'Administrador')
            ->exists();
    }

    /**
     * L'usuari és administrador d'almenys un projecte.
     */
    protected function esAdminDeAlgun(): bool
    {
        return DB::table('projecte__usuaris')
            ->join('rols', 'rols.id', '=', 'projecte__usuaris.rols_id')
            ->where('projecte__usuaris.users_id', Auth::id())
            ->where('rols.nom', 'Administrador')
            ->exists();
    }
}
