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
    | OAuth token URL
    |--------------------------------------------------------------------------
    |
    | Full URL for POST /oauth/token. If null, {base_url}/oauth/token is used.
    |
    */

    'oauth_url' => env('SPPAY_OAUTH_URL'),

    /*
    |--------------------------------------------------------------------------
    | OAuth password grant (optional)
    |--------------------------------------------------------------------------
    |
    | Used by oauthPasswordGrant() when you want credentials from .env.
    | Never commit real secrets; use .env only (and exclude from VCS).
    |
    */

    'client_id' => env('SPPAY_CLIENT_ID'),

    'client_secret' => env('SPPAY_CLIENT_SECRET'),

    'username' => env('SPPAY_USERNAME'),

    'password' => env('SPPAY_PASSWORD'),

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
