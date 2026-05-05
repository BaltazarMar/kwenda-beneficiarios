<?php

// config/kobotoolbox.php
// Coloca este ficheiro em: config/kobotoolbox.php

return [

    /*
    |--------------------------------------------------------------------------
    | URL Base do KoBoToolbox
    |--------------------------------------------------------------------------
    | Servidor Global: https://kf.kobotoolbox.org
    | Servidor EU:     https://eu.kobotoolbox.org
    */
    'base_url' => env('KOBO_BASE_URL', 'https://kf.kobotoolbox.org'),

    /*
    |--------------------------------------------------------------------------
    | Token da API
    |--------------------------------------------------------------------------
    | Obtém em: KoBoToolbox > Conta > API Token
    | Adiciona ao .env: KOBO_API_TOKEN=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    */
    'api_token' => env('KOBO_API_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | UID do Formulário (Asset UID)
    |--------------------------------------------------------------------------
    | Obtém no URL do teu formulário:
    | https://kf.kobotoolbox.org/#/forms/AQUI_ESTA_O_UID/summary
    | Adiciona ao .env: KOBO_ASSET_UID=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    */
    'asset_uid' => env('KOBO_ASSET_UID'),

];