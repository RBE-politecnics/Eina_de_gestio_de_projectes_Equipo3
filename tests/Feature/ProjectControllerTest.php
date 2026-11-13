<?php

use App\Models\Projects;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a project from the dashboard modal', function () {
    $user = User::query()->create([
        'email' => 'project-owner@example.com',
        'password' => 'password',
    ]);

    $response = $this->actingAs($user)->post(route('projects.store'), [
        'source' => 'dashboard',
        'nom' => 'Projecte nou',
        'descripcion' => 'Descripció del projecte',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertDatabaseHas('projects', [
        'nom' => 'Projecte nou',
        'descripcion' => 'Descripció del projecte',
        'color' => '#FFFFFF',
    ]);
});

it('rejects a project without a name', function () {
    $user = User::query()->create([
        'email' => 'project-owner@example.com',
        'password' => 'password',
    ]);

    $response = $this->actingAs($user)->from(route('dashboard'))->post(route('projects.store'), [
        'source' => 'dashboard',
        'descripcion' => 'Descripció del projecte',
    ]);

    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHasErrors('nom');
    expect(Projects::query()->count())->toBe(0);
});
