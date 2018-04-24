<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],


    'qq' => [
        'client_id' => env('QQ_KEY'),         // Your QQ Client ID
        'client_secret' => env('QQ_SECRET'), // Your QQ Client Secret
        'redirect' => env('QQ_REDIRECT_URI'),
    ],

    'weibo' => [
        'client_id' => env('WEIBO_KEY'),         // Your WeiBo Client ID
        'client_secret' => env('WEIBO_SECRET'), // Your WeiBo Client Secret
        'redirect' => env('WEIBO_REDIRECT_URI'),
    ],

    'github' => [
        'client_id' => env('GITHUB_KEY'),         // Your GitHub Client ID
        'client_secret' => env('GITHUB_SECRET'), // Your GitHub Client Secret
        'redirect' => env('GITHUB_REDIRECT_URI'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_KEY'),         // Your Google Client ID
        'client_secret' => env('GOOGLE_SECRET'), // Your Google Client Secret
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],
];
