<?php

namespace Clanofartisans\EveEsi\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        $scopes = config('eve-esi.scopes');

        session(['pre-auth' => session()->previousUrl()]);

        return Socialite::driver('eveonline')->scopes($scopes)->redirect();
    }

    public function callback(): RedirectResponse
    {
        $esiUser = Socialite::driver('eveonline')->user();

        $user = User::updateOrCreate([
            'character_id' => $esiUser->character_id,
        ], [
            'character_owner_hash' => $esiUser->character_owner_hash,
            'character_name' => $esiUser->character_name,
            'token' => $esiUser->token,
            'refreshToken' => $esiUser->refreshToken,
            'expiresIn' => $esiUser->expiresIn,
        ]);

        Auth::login($user, true);

        return redirect(session('pre-auth'));
    }

    public function logout(Request $request): RedirectResponse
    {
        $previous = session()->previousUrl();

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect($previous);
    }
}
