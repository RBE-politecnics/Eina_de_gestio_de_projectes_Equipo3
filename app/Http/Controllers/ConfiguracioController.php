<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ComprovaPermisos;
use App\Models\TipusTasca;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConfiguracioController
{
    use ComprovaPermisos;

    public function index(): View
    {
        return view('pages.configuracio', [
            'tipus' => TipusTasca::orderBy('id')->get(),
            'estats' => DB::table('estats')->orderBy('id')->get(),
            'potGestionar' => $this->esAdminDeAlgun(),
        ]);
    }

    public function guardarTipus(Request $request): RedirectResponse
    {
        $this->nomesAdministradors();

        $dades = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        TipusTasca::create($dades);

        return back()->with('status', 'Tipus de tasca creat.');
    }

    public function actualitzarTipus(Request $request, TipusTasca $tipus): RedirectResponse
    {
        $this->nomesAdministradors();

        $dades = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $tipus->update($dades);

        return back()->with('status', 'Tipus de tasca actualitzat.');
    }

    public function esborrarTipus(TipusTasca $tipus): RedirectResponse
    {
        $this->nomesAdministradors();

        // Les tasques d'aquest tipus es queden sense tipus (nullOnDelete)
        $tipus->delete();

        return back()->with('status', 'Tipus de tasca eliminat.');
    }

    public function guardarEstat(Request $request): RedirectResponse
    {
        $this->nomesAdministradors();

        $dades = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
        ]);

        DB::table('estats')->insert([
            'nom' => $dades['nom'],
            'bloqueja' => $request->boolean('bloqueja'),
        ]);

        return back()->with('status', 'Estat creat.');
    }

    public function actualitzarEstat(Request $request, int $estat): RedirectResponse
    {
        $this->nomesAdministradors();

        $dades = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
        ]);

        DB::table('estats')->where('id', $estat)->update([
            'nom' => $dades['nom'],
            'bloqueja' => $request->boolean('bloqueja'),
        ]);

        return back()->with('status', 'Estat actualitzat.');
    }

    public function esborrarEstat(int $estat): RedirectResponse
    {
        $this->nomesAdministradors();

        if (DB::table('tasques')->where('estats_id', $estat)->exists()) {
            return back()->with('error', 'No es pot eliminar un estat que té tasques.');
        }

        if (DB::table('estats')->count() <= 2) {
            return back()->with('error', 'Hi ha d\'haver almenys dos estats.');
        }

        DB::table('estats')->where('id', $estat)->delete();

        return back()->with('status', 'Estat eliminat.');
    }

    private function nomesAdministradors(): void
    {
        if (! $this->esAdminDeAlgun()) {
            abort(403);
        }
    }
}
