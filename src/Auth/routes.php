<?php

use Clanofartisans\EveEsi\Auth\ESIAuth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::group(['middleware' => ['web']], function () {
    Route::get('/auth/redirect', function () {
        $scopes = [
            'publicData',
            'esi-search.search_structures.v1',
            'esi-universe.read_structures.v1',
            'esi-markets.structure_markets.v1',
            'esi-corporations.read_structures.v1',
            'esi-characters.read_medals.v1',
            'esi-corporations.read_medals.v1'
        ];
        return Socialite::driver('eveonline')->scopes($scopes)->redirect();
    });

    Route::get('/auth/callback', function () {
        $user = Socialite::driver('eveonline')->user();

        ESIAuth::updateOrCreate(
            ['character_id' => $user->character_id],
            [
                'character_owner_hash' => $user->character_owner_hash,
                'character_name' => $user->character_name,
                'token' => $user->token,
                'refreshToken' => $user->refreshToken,
                'expiresIn' => $user->expiresIn
            ]
        );

        return 'OK';
    });
});
