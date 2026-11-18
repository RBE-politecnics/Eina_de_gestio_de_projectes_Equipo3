<?php

use App\Models\Tasques;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

function prepararTasca(): array
{
    $usuari = User::query()->create(['email' => 'responsable@example.com', 'password' => 'password']);
    $altre = User::query()->create(['email' => 'altre@example.com', 'password' => 'password']);

    $projecteId = DB::table('projects')->insertGetId(['nom' => 'Projecte', 'created_at' => now(), 'updated_at' => now()]);
    $enCurs = DB::table('estats')->insertGetId(['nom' => 'En curs']);
    $feta = DB::table('estats')->insertGetId(['nom' => 'Completada']);

    $tasca = Tasques::query()->create([
        'projecte_id' => $projecteId,
        'users_id' => $usuari->id,
        'nom' => 'Tasca de prova',
        'estats_id' => $enCurs,
        'progres' => 50,
    ]);

    return [$usuari, $altre, $tasca, $feta];
}

it('deixa al responsable canviar l\'estat de la seva tasca', function () {
    [$usuari, , $tasca, $feta] = prepararTasca();

    $this->actingAs($usuari)
        ->put(route('tasques.update', $tasca), ['estats_id' => $feta])
        ->assertRedirect(route('tauler', $tasca->projecte_id));

    expect($tasca->fresh()->estats_id)->toBe($feta);
    expect($tasca->fresh()->progres)->toBe(100);
});

it('no deixa canviar una tasca feta', function () {
    [$usuari, , $tasca, $feta] = prepararTasca();
    $tasca->update(['estats_id' => $feta]);

    $this->actingAs($usuari)
        ->put(route('tasques.update', $tasca), ['nom' => 'Nom nou'])
        ->assertSessionHas('error');

    expect($tasca->fresh()->nom)->toBe('Tasca de prova');
});

it('no deixa editar la tasca d\'un altre si no ets administrador', function () {
    [, $altre, $tasca] = prepararTasca();

    $this->actingAs($altre)
        ->put(route('tasques.update', $tasca), ['nom' => 'Nom nou'])
        ->assertForbidden();
});

it('deixa eliminar la tasca al responsable', function () {
    [$usuari, , $tasca] = prepararTasca();

    $this->actingAs($usuari)->delete(route('tasques.destroy', $tasca));

    expect(Tasques::query()->count())->toBe(0);
});
