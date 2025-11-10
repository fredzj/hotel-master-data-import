<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'apaleo' => [
        'client_id' => env('APALEO_CLIENT_ID'),
        'client_secret' => env('APALEO_CLIENT_SECRET'),
        'base_url' => env('APALEO_BASE_URL', 'https://api.apaleo.com'),
        'identity_url' => env('APALEO_IDENTITY_URL', 'https://identity.apaleo.com'),
    ],

    'mews' => [
        'client_token' => env('MEWS_CLIENT_TOKEN'),
        'access_token' => env('MEWS_ACCESS_TOKEN'),
        'client' => env('MEWS_CLIENT', 'HotelMasterDataImport'),
        'base_url' => env('MEWS_BASE_URL', 'https://api.mews.com'),
        'demo_base_url' => env('MEWS_DEMO_BASE_URL', 'https://api.mews-demo.com'),
    ],

];
