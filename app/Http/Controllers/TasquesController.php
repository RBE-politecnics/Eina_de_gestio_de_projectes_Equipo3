<?php

namespace App\Http\Controllers;

use App\Models\Projecte_Usuari;
use App\Models\Rols;
use App\Models\Tasques;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TasquesController
{
    public function store(Request $request): RedirectResponse
    {
        $tasca = $request->validate([
            'projecte_id' => ['required', 'integer', 'exists:projects,id'],
            'nom' => ['required', 'string', 'max:255'],
            'data_finalitzacio' => ['nullable', 'date'],
            'estats_id' => ['required', 'integer', 'exists:estats,id'],
            'comentaris' => ['nullable', 'string'],
            'progres' => ['nullable', 'integer', 'between:0,100'],
            'color' => ['nullable', 'string', 'max:255'],
        ]);

        $tasca['users_id'] = $request->user()->id;

        Tasques::create($tasca);

        return redirect()->route('tauler', $tasca['projecte_id'])->with('status', 'Tasca creada correctament.');
    }

    public function update(Request $request, Tasques $tasca): RedirectResponse
    {
        if (! $this->potModificar($request, $tasca)) {
            abort(403);
        }

        if ($this->estaFeta($tasca)) {
            return back()->with('error', 'Una tasca feta ja no es pot canviar.');
        }

        $dades = $request->validate([
            'nom' => ['sometimes', 'required', 'string', 'max:255'],
            'comentaris' => ['nullable', 'string'],
            'data_finalitzacio' => ['nullable', 'date'],
            'estats_id' => ['sometimes', 'required', 'integer', 'exists:estats,id'],
        ]);

        // El progrés va amb l'estat: pendent 0, en curs 50 i feta 100
        if (isset($dades['estats_id']) && $dades['estats_id'] != $tasca->estats_id) {
            $nomEstat = DB::table('estats')->where('id', $dades['estats_id'])->value('nom');

            $dades['progres'] = match ($nomEstat) {
                'En curs' => 50,
                'Completada', 'Feta' => 100,
                default => 0,
            };
        }

        $tasca->update($dades);

        return redirect()->route('tauler', $tasca->projecte_id)->with('status', 'Tasca actualitzada.');
    }

    public function destroy(Request $request, Tasques $tasca): RedirectResponse
    {
        if (! $this->potModificar($request, $tasca)) {
            abort(403);
        }

        if ($this->estaFeta($tasca)) {
            return back()->with('error', 'Una tasca feta ja no es pot canviar.');
        }

        $tasca->delete();

        return redirect()->route('tauler', $tasca->projecte_id)->with('status', 'Tasca eliminada.');
    }

    /**
     * Pot modificar la tasca el seu responsable o l'administrador del projecte.
     */
    private function potModificar(Request $request, Tasques $tasca): bool
    {
        $usuariId = $request->user()->id;

        $esAdmin = Projecte_Usuari::where('projecte_id', $tasca->projecte_id)
            ->where('users_id', $usuariId)
            ->where('rols_id', Rols::where('nom', 'Administrador')->value('id'))
            ->exists();

        return $esAdmin || $tasca->users_id == $usuariId;
    }

    private function estaFeta(Tasques $tasca): bool
    {
        return in_array($tasca->estat?->nom, ['Completada', 'Feta']);
    }
}
