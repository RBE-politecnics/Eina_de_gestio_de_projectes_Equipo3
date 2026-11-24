<?php

namespace App\Http\Resources;

use App\Models\Tasques;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Tasques
 */
class TascaResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $esAdmin = (bool) $request->attributes->get('esAdmin', false);

        return [
            'id' => $this->id,
            'projecte_id' => $this->projecte_id,
            'nom' => $this->nom,
            'comentaris' => $this->comentaris ?? '',
            'data' => $this->data_finalitzacio?->format('Y-m-d') ?? '',
            'data_text' => $this->data_finalitzacio?->format('d/m/Y') ?? 'Sense data',
            'estats_id' => $this->estats_id,
            'estat_nom' => $this->estat?->nom,
            'bloquejada' => (bool) $this->estat?->bloqueja,
            'progres' => $this->progres,
            'tipus_id' => $this->tipus_id,
            'tipus_nom' => $this->tipus?->nom ?? 'Sense tipus',
            'tipus_color' => $this->tipus?->color ?? '#6b7280',
            'users_id' => $this->users_id,
            'responsable' => $this->usuari?->email ?? 'Sense assignar',
            'pot' => $esAdmin || $this->users_id == $request->user()?->id,
        ];
    }
}
