<?php

return [
    // The application's Client ID from EVE Developers
    'client_id' => env('EVEONLINE_CLIENT_ID'),

    // The application's Secret Key from EVE Developers
    'client_secret' => env('EVEONLINE_CLIENT_SECRET'),

    // The application's Callback URL from EVE Developers
    'redirect_uri' => env('EVEONLINE_REDIRECT_URI'),

    // The User-Agent to send with all ESI requests
    'user_agent' => env('EVEONLINE_ESI_USER_AGENT'),

    // The character ID to use for authenticated ESI routes
    'auth_character_id' => env('EVEONLINE_AUTH_CHARACTER_ID'),

    // The table to use for storing ESI authentication data
    'auth_table' => env('EVEONLINE_ESI_AUTH_TABLE'),

    // The scopes to be requested for ESI SSO
    'scopes' => [

        'sso' => [
            'publicData',
            'esi-location.read_location.v1',
            'esi-location.read_online.v1'
        ],

        'yams' => [
            'publicData',
            'esi-search.search_structures.v1',
            'esi-universe.read_structures.v1',
            'esi-markets.structure_markets.v1',
            'esi-corporations.read_structures.v1',
            'esi-characters.read_medals.v1',
            'esi-corporations.read_medals.v1'
        ]

    ]
];
