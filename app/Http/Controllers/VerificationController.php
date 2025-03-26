<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Auth\Events\Verified;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class VerificationController extends Controller
{
    public function verify($id, $hash)
    {
        $user = User::findOrFail($id);
//        dd(Hash::check($user->getEmailForVerification(), $hash));
        // Provera da li je hash validan
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(["message" => "Verifikacija nije uspela"], 400);
        }

        // Potvrđivanje email-a
        if ($user->hasVerifiedEmail()) {
            return view('successfullyVerify')->with('url', env('FRONTEND_URL') . '/login');
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return view('successfullyVerify')->with('url', env('FRONTEND_URL') . '/login');
    }

    public function resend(Request $request)
    {
        $user = $request->user();

        // Ako je već verifikovan, nemoj slati ponovo
        if ($user->hasVerifiedEmail()) {
            return response()->json(["message" => "Email je već verifikovan."], 400);
        }

        // Pošaljite verifikaciju ponovo
        $user->sendEmailVerificationNotification();

        return response()->json(["message" => "Verifikacioni email je ponovo poslat."], 200);
    }
}
