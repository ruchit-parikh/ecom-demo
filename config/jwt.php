<?php

return [
    /*
    |--------------------------------------------------------------------------
    | JWT Pass Phrase
    |--------------------------------------------------------------------------
    |
    | This value is the string phrase that will be used to generate and validate
    | your application's JWT tokens. It can be directly generated using setup.sh file
    */
    'pass_phrase' => env('JWT_PASSPHRASE', ''),

    /*
    |--------------------------------------------------------------------------
    | JWT Access Token Expiry
    |--------------------------------------------------------------------------
    |
    | This value is the modifier for php's DateTimeImmutable object. It will define
    | amount of time for which generated short lived jwt access token is considered valid.
    */
    'access_token_expiry' => env('JWT_ACCESS_TOKEN_EXPIRY', '1 hour'),

    /*
    |--------------------------------------------------------------------------
    | JWT Refresh Token Expiry
    |--------------------------------------------------------------------------
    |
    | This value is the modifier for php's DateTimeImmutable object. It will define
    | amount of time for which generated long lived jwt refresh token is considered valid.
    */
    'refresh_token_expiry' => env('JWT_REFRESH_TOKEN_EXPIRY', '3 months'),
];
