<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    |
    | SPPay API host (no trailing slash). Example: https://engine.sppay.dev
    |
    */

    'base_url' => env('SPPAY_BASE_URL', 'https://engine.sppay.dev'),

    /*
    |--------------------------------------------------------------------------
    | Access token
    |--------------------------------------------------------------------------
    |
    | Bearer token from POST /oauth/token. Leave null if you set the token
    | at runtime via Sppay::setAccessToken() or the client constructor.
    |
    */

    'access_token' => env('SPPAY_ACCESS_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | HTTP client
    |--------------------------------------------------------------------------
    */

    'timeout' => (float) env('SPPAY_TIMEOUT', 30),

    'connect_timeout' => (float) env('SPPAY_CONNECT_TIMEOUT', 10),

];
