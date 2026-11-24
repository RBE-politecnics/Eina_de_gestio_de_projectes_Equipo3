<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Concerns\ComprovaPermisos;
use App\Http\Resources\TascaResource;
use App\Models\Projects;
use App\Models\Tasques;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TascaController
{
    use ComprovaPermisos;

    public function projectes(): JsonResponse
    {
        $ids = DB::table('projecte__usuaris')->where('users_id', Auth::id())->pluck('projecte_id');

        return response()->json(Projects::whereIn('id', $ids)->orderBy('nom')->get(['id', 'nom', 'descripcion', 'color']));
    }

    public function index(Request $request, int $projecteId): AnonymousResourceCollection
    {
        if (! $this->esMembre($projecteId)) {
            abort(403);
        }

        $request->attributes->set('esAdmin', $this->esAdmin($projecteId));

        return TascaResource::collection(
            Tasques::with(['estat', 'usuari', 'tipus'])->where('projecte_id', $projecteId)->orderBy('id')->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $dades = $request->validate([
            'projecte_id' => ['required', 'integer', 'exists:projects,id'],
            'nom' => ['required', 'string', 'max:255'],
            'comentaris' => ['nullable', 'string'],
            'data_finalitzacio' => ['nullable', 'date'],
            'estats_id' => ['required', 'integer', 'exists:estats,id'],
            'tipus_id' => ['nullable', 'integer', 'exists:tipus_tasca,id'],
            'users_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        if (! $this->esMembre($dades['projecte_id'])) {
            abort(403);
        }

        $dades['users_id'] = $this->responsableValid($dades['projecte_id'], $dades['users_id'] ?? null);
        $dades['progres'] = $this->progresPerEstat($dades['estats_id']);

        $tasca = Tasques::create($dades);

        return $this->resposta($request, $tasca, 201);
    }

    public function update(Request $request, Tasques $tasca): JsonResponse
    {
        $this->comprovaModificar($tasca);

        $dades = $request->validate([
            'nom' => ['sometimes', 'required', 'string', 'max:255'],
            'comentaris' => ['nullable', 'string'],
            'data_finalitzacio' => ['nullable', 'date'],
            'estats_id' => ['sometimes', 'required', 'integer', 'exists:estats,id'],
            'tipus_id' => ['nullable', 'integer', 'exists:tipus_tasca,id'],
            'users_id' => ['sometimes', 'required', 'integer', 'exists:users,id'],
        ]);

        if (isset($dades['users_id'])) {
            $dades['users_id'] = $this->responsableValid($tasca->projecte_id, $dades['users_id']);
        }

        if (isset($dades['estats_id']) && $dades['estats_id'] != $tasca->estats_id) {
            $dades['progres'] = $this->progresPerEstat($dades['estats_id']);
        }

        $tasca->update($dades);

        return $this->resposta($request, $tasca);
    }

    public function estat(Request $request, Tasques $tasca): JsonResponse
    {
        $this->comprovaModificar($tasca);

        $dades = $request->validate([
            'estats_id' => ['required', 'integer', 'exists:estats,id'],
        ]);

        $tasca->update([
            'estats_id' => $dades['estats_id'],
            'progres' => $this->progresPerEstat($dades['estats_id']),
        ]);

        return $this->resposta($request, $tasca);
    }

    public function assignarMe(Request $request, Tasques $tasca): JsonResponse
    {
        if (! $this->esMembre($tasca->projecte_id)) {
            abort(403);
        }

        if ($tasca->estat?->bloqueja) {
            abort(422, 'Una tasca feta ja no es pot canviar.');
        }

        $tasca->update(['users_id' => Auth::id()]);

        return $this->resposta($request, $tasca);
    }

    public function destroy(Tasques $tasca): Response
    {
        $this->comprovaModificar($tasca);

        $tasca->delete();

        return response()->noContent();
    }

    /**
     * Només el responsable o l'administrador poden canviar una tasca, i una tasca feta queda bloquejada.
     */
    private function comprovaModificar(Tasques $tasca): void
    {
        if (! ($this->esAdmin($tasca->projecte_id) || $tasca->users_id == Auth::id())) {
            abort(403);
        }

        if ($tasca->estat?->bloqueja) {
            abort(422, 'Una tasca feta ja no es pot canviar.');
        }
    }

    /**
     * Qualsevol membre s'assigna les tasques a ell mateix; l'administrador les assigna a qualsevol membre.
     */
    private function responsableValid(int $projecteId, ?int $triat): int
    {
        if ($triat === null || $triat == Auth::id()) {
            return Auth::id();
        }

        $esMembre = DB::table('projecte__usuaris')
            ->where('projecte_id', $projecteId)
            ->where('users_id', $triat)
            ->exists();

        if (! $this->esAdmin($projecteId) || ! $esMembre) {
            throw ValidationException::withMessages([
                'users_id' => "Només l'administrador pot assignar la tasca a un altre membre del projecte.",
            ]);
        }

        return $triat;
    }

    /**
     * L'estat bloquejat és 100, el primer és 0 i els del mig 50.
     */
    private function progresPerEstat(int $estatId): int
    {
        $estat = DB::table('estats')->where('id', $estatId)->first();

        if ($estat->bloqueja) {
            return 100;
        }

        return $estatId == DB::table('estats')->min('id') ? 0 : 50;
    }

    private function resposta(Request $request, Tasques $tasca, int $codi = 200): JsonResponse
    {
        $request->attributes->set('esAdmin', $this->esAdmin($tasca->projecte_id));

        $dades = (new TascaResource($tasca->load(['estat', 'usuari', 'tipus'])))->resolve($request);

        return response()->json(['data' => $dades], $codi);
    }
}
