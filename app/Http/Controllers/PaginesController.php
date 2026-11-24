<?php

namespace App\Http\Controllers;

use App\Models\Estats;
use App\Models\Projecte_Usuari;
use App\Models\Projects;
use App\Models\Rols;
use App\Models\Tasques;
use App\Models\TipusTasca;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaginesController
{
    public function projectes(Request $request): View
    {
        $idsProjectes = Projecte_Usuari::where('users_id', Auth::id())->pluck('projecte_id');

        $projectes = Projects::whereIn('id', $idsProjectes)
            ->where('nom', 'like', '%'.$request->input('q').'%')
            ->latest()
            ->get();

        return view('pages.projectes', [
            'projectes' => $projectes,
            'tasques' => Tasques::whereIn('projecte_id', $idsProjectes)->get(),
            'cerca' => $request->input('q'),
        ]);
    }

    public function tauler(?int $projecteId = null): View
    {
        $idsProjectes = Projecte_Usuari::where('users_id', Auth::id())->pluck('projecte_id');
        $projectes = Projects::whereIn('id', $idsProjectes)->orderBy('nom')->get();

        // Si no ens diuen quin projecte, agafem el primer de l'usuari
        $projecte = $projectes->firstWhere('id', $projecteId) ?? $projectes->first();

        $esAdmin = false;
        $membres = collect();

        if ($projecte) {
            $esAdmin = Projecte_Usuari::where('projecte_id', $projecte->id)
                ->where('users_id', Auth::id())
                ->where('rols_id', Rols::where('nom', 'Administrador')->value('id'))
                ->exists();

            // Els membres serveixen per triar el responsable d'una tasca
            $membres = DB::table('projecte__usuaris')
                ->join('users', 'users.id', '=', 'projecte__usuaris.users_id')
                ->where('projecte__usuaris.projecte_id', $projecte->id)
                ->select('users.id', 'users.email')
                ->get();
        }

        // Les tasques no es carreguen aquí: les demana el JavaScript a l'API
        return view('projects.tauler', [
            'projectes' => $projectes,
            'projecte' => $projecte,
            'esAdmin' => $esAdmin,
            'membres' => $membres,
            'estatsDisponibles' => Estats::orderBy('id')->get(),
            'tipusDisponibles' => TipusTasca::orderBy('id')->get(),
        ]);
    }

    public function equip(Request $request): View
    {
        $idsProjectes = Projecte_Usuari::where('users_id', Auth::id())->pluck('projecte_id');
        $projectes = Projects::whereIn('id', $idsProjectes)->orderBy('nom')->get();

        $projecte = $projectes->firstWhere('id', $request->input('projecte')) ?? $projectes->first();

        $membres = collect();
        $esAdmin = false;

        if ($projecte) {
            $membres = DB::table('projecte__usuaris')
                ->join('users', 'users.id', '=', 'projecte__usuaris.users_id')
                ->join('rols', 'rols.id', '=', 'projecte__usuaris.rols_id')
                ->where('projecte__usuaris.projecte_id', $projecte->id)
                ->select('users.id', 'users.email', 'rols.nom as rol')
                ->get();

            $esAdmin = $membres->where('id', Auth::id())->where('rol', 'Administrador')->count() > 0;
        }

        return view('pages.equip', [
            'projectes' => $projectes,
            'projecte' => $projecte,
            'membres' => $membres,
            'esAdmin' => $esAdmin,
            'cerca' => $request->input('q'),
        ]);
    }

    public function afegirMembre(Request $request): RedirectResponse
    {
        $dades = $request->validate([
            'projecte_id' => ['required', 'integer', 'exists:projects,id'],
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'No hi ha cap usuari amb aquest correu.',
        ]);

        $esAdmin = Projecte_Usuari::where('projecte_id', $dades['projecte_id'])
            ->where('users_id', Auth::id())
            ->where('rols_id', Rols::where('nom', 'Administrador')->value('id'))
            ->exists();

        if (! $esAdmin) {
            abort(403);
        }

        $usuari = User::where('email', $dades['email'])->first();

        $jaEsMembre = Projecte_Usuari::where('projecte_id', $dades['projecte_id'])
            ->where('users_id', $usuari->id)
            ->exists();

        if (! $jaEsMembre) {
            DB::table('projecte__usuaris')->insert([
                'projecte_id' => $dades['projecte_id'],
                'users_id' => $usuari->id,
                'rols_id' => Rols::where('nom', 'Col·laborador')->value('id') ?? DB::table('rols')->insertGetId(['nom' => 'Col·laborador']),
            ]);
        }

        return redirect()->route('equip', ['projecte' => $dades['projecte_id']])
            ->with('status', 'Col·laborador afegit.');
    }
}
