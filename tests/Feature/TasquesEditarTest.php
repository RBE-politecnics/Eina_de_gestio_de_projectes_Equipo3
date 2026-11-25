<?php

use App\Models\Tasques;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

/**
 * Un projecte amb un administrador, un col·laborador i una tasca del col·laborador.
 *
 * @return array{admin: User, membre: User, estrany: User, tasca: Tasques, feta: int, projecte: int}
 */
function prepararProjecte(): array
{
    $admin = User::query()->create(['email' => 'admin@example.com', 'password' => 'password']);
    $membre = User::query()->create(['email' => 'membre@example.com', 'password' => 'password']);
    $estrany = User::query()->create(['email' => 'estrany@example.com', 'password' => 'password']);

    $projecte = DB::table('projects')->insertGetId(['nom' => 'Projecte', 'created_at' => now(), 'updated_at' => now()]);
    $rolAdmin = DB::table('rols')->insertGetId(['nom' => 'Administrador']);
    $rolMembre = DB::table('rols')->insertGetId(['nom' => 'Col·laborador']);

    DB::table('projecte__usuaris')->insert([
        ['projecte_id' => $projecte, 'users_id' => $admin->id, 'rols_id' => $rolAdmin],
        ['projecte_id' => $projecte, 'users_id' => $membre->id, 'rols_id' => $rolMembre],
    ]);

    $perFer = DB::table('estats')->insertGetId(['nom' => 'Per fer', 'bloqueja' => false]);
    DB::table('estats')->insertGetId(['nom' => 'En progrés', 'bloqueja' => false]);
    $feta = DB::table('estats')->insertGetId(['nom' => 'Feta', 'bloqueja' => true]);

    $tasca = Tasques::query()->create([
        'projecte_id' => $projecte,
        'users_id' => $membre->id,
        'nom' => 'Tasca de prova',
        'estats_id' => $perFer,
    ]);

    return ['admin' => $admin, 'membre' => $membre, 'estrany' => $estrany, 'tasca' => $tasca, 'feta' => $feta, 'projecte' => $projecte];
}

it('deixa al responsable canviar l\'estat de la seva tasca i ajusta el progrés', function () {
    $p = prepararProjecte();

    $this->actingAs($p['membre'])
        ->patchJson(route('api.tasques.estat', $p['tasca']), ['estats_id' => $p['feta']])
        ->assertOk()
        ->assertJsonPath('data.bloquejada', true);

    expect($p['tasca']->fresh()->estats_id)->toBe($p['feta']);
    expect($p['tasca']->fresh()->progres)->toBe(100);
});

it('no deixa canviar una tasca feta', function () {
    $p = prepararProjecte();
    $p['tasca']->update(['estats_id' => $p['feta']]);

    $this->actingAs($p['membre'])
        ->putJson(route('api.tasques.update', $p['tasca']), ['nom' => 'Nom nou'])
        ->assertUnprocessable();

    expect($p['tasca']->fresh()->nom)->toBe('Tasca de prova');
});

it('no deixa editar la tasca d\'un altre a qui no és administrador', function () {
    $p = prepararProjecte();
    $altre = User::query()->create(['email' => 'altre@example.com', 'password' => 'password']);

    $this->actingAs($altre)
        ->putJson(route('api.tasques.update', $p['tasca']), ['nom' => 'Nom nou'])
        ->assertForbidden();
});

it('deixa a l\'administrador editar la tasca d\'un altre i assignar-la', function () {
    $p = prepararProjecte();

    $this->actingAs($p['admin'])
        ->putJson(route('api.tasques.update', $p['tasca']), ['nom' => 'Nom nou', 'users_id' => $p['admin']->id])
        ->assertOk()
        ->assertJsonPath('data.responsable', 'admin@example.com');
});

it('no deixa a un col·laborador assignar la tasca a un altre membre', function () {
    $p = prepararProjecte();

    $this->actingAs($p['membre'])
        ->putJson(route('api.tasques.update', $p['tasca']), ['users_id' => $p['admin']->id])
        ->assertUnprocessable();
});

it('deixa a un membre assignar-se una tasca', function () {
    $p = prepararProjecte();

    $this->actingAs($p['admin'])
        ->patchJson(route('api.tasques.assignar', $p['tasca']))
        ->assertOk();

    expect($p['tasca']->fresh()->users_id)->toBe($p['admin']->id);
});

it('només deixa veure les tasques als membres del projecte', function () {
    $p = prepararProjecte();

    $this->actingAs($p['estrany'])->getJson(route('api.tasques.index', $p['projecte']))->assertForbidden();
    $this->actingAs($p['membre'])->getJson(route('api.tasques.index', $p['projecte']))->assertOk()->assertJsonCount(1, 'data');
});

it('deixa crear una tasca als membres i la posa al seu nom', function () {
    $p = prepararProjecte();
    $perFer = DB::table('estats')->where('nom', 'Per fer')->value('id');

    $this->actingAs($p['membre'])
        ->postJson(route('api.tasques.store'), ['projecte_id' => $p['projecte'], 'nom' => 'Nova', 'estats_id' => $perFer])
        ->assertCreated()
        ->assertJsonPath('data.responsable', 'membre@example.com');

    $this->actingAs($p['estrany'])
        ->postJson(route('api.tasques.store'), ['projecte_id' => $p['projecte'], 'nom' => 'Intrusa', 'estats_id' => $perFer])
        ->assertForbidden();
});

it('deixa eliminar la tasca al responsable', function () {
    $p = prepararProjecte();

    $this->actingAs($p['membre'])->deleteJson(route('api.tasques.destroy', $p['tasca']))->assertNoContent();

    expect(Tasques::query()->count())->toBe(0);
});

it('inicia sessió i es registra per l\'API', function () {
    $usuari = User::query()->create(['email' => 'api@example.com', 'password' => 'password']);

    $this->postJson(route('api.login'), ['email' => 'api@example.com', 'password' => 'malament'])->assertUnprocessable();
    $this->postJson(route('api.login'), ['email' => 'api@example.com', 'password' => 'password'])->assertOk();
    $this->assertAuthenticatedAs($usuari);

    $this->postJson(route('api.register'), [
        'email' => 'nou@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertCreated();
});

it('només deixa a l\'administrador editar i eliminar el projecte', function () {
    $p = prepararProjecte();

    $this->actingAs($p['membre'])
        ->put(route('projects.update', $p['projecte']), ['nom' => 'Hackeig'])
        ->assertForbidden();

    $this->actingAs($p['admin'])
        ->put(route('projects.update', $p['projecte']), ['nom' => 'Nom nou'])
        ->assertRedirect();
    expect(DB::table('projects')->where('id', $p['projecte'])->value('nom'))->toBe('Nom nou');

    $this->actingAs($p['membre'])->delete(route('projects.destroy', $p['projecte']))->assertForbidden();
    $this->actingAs($p['admin'])->delete(route('projects.destroy', $p['projecte']))->assertRedirect(route('projectes'));
    expect(DB::table('projects')->count())->toBe(0);
});

it('només deixa als administradors gestionar tipus i estats', function () {
    $p = prepararProjecte();

    $this->actingAs($p['membre'])
        ->post(route('configuracio.tipus.guardar'), ['nom' => 'Nou', 'color' => '#112233'])
        ->assertForbidden();

    $this->actingAs($p['admin'])
        ->post(route('configuracio.tipus.guardar'), ['nom' => 'Nou', 'color' => '#112233'])
        ->assertRedirect();

    $this->assertDatabaseHas('tipus_tasca', ['nom' => 'Nou']);
});
