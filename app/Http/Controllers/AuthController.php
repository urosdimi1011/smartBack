<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            // $token = $user->createToken('authToken')->plainTextToken;
            return response()->json(['message' => 'Pogresan email ili lozinka'], 404);
        }

        $user = Auth::guard('api')->user();

        if (!$user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Morate verifikovati email pre prijave.'], 403);
        }
        // Ako je login uspešan, generiši JWT token (ako koristiš Sanctum/Passport)

        return response()->json([
            'token' => $token,
            'user' => Auth::guard('api')->user(),
        ]);

        // return response()->json(['token' => $token, 'user' => $user], 200);
    }

    public function refreshToken()
    {
        $user = auth()->user();

        // Provera da li korisnik postoji
        if (!$user) {
            return response()->json(['message' => 'Korisnik nije pronađen'], 404);
        }

        // dd("Uslii");
        return response()->json([
            'token' => auth()->refresh(),
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me()
    {
        return response()->json(Auth::user());
    }
}
