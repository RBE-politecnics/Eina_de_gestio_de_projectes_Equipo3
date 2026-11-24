<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthApiController
{
    public function login(Request $request): JsonResponse
    {
        $credencials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credencials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Les credencials no són vàlides.',
            ]);
        }

        $request->session()->regenerate();

        return response()->json(['usuari' => ['id' => Auth::id(), 'email' => Auth::user()->email]]);
    }

    public function register(Request $request): JsonResponse
    {
        $dades = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $usuari = User::create([
            'email' => $dades['email'],
            'password' => Hash::make($dades['password']),
        ]);

        Auth::login($usuari);
        $request->session()->regenerate();

        return response()->json(['usuari' => ['id' => $usuari->id, 'email' => $usuari->email]], 201);
    }

    public function logout(Request $request): Response
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }

    public function usuari(Request $request): JsonResponse
    {
        return response()->json(['id' => $request->user()->id, 'email' => $request->user()->email]);
    }
}
