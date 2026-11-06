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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    'web' => [
        'token' => env('WEB_API_TOKEN'),
    ],

    'twilio' => [
        'cl' => [
            'sid' => env('TWILIO_AUTH_SID_CL'),
            'token' => env('TWILIO_AUTH_TOKEN_CL'),
            'whatsapp_from' => env('TWILIO_WHATSAPP_FROM_CL'),
        ],
        'ar' => [
            'sid' => env('TWILIO_AUTH_SID_AR'),
            'token' => env('TWILIO_AUTH_TOKEN_AR'),
            'whatsapp_from' => env('TWILIO_WHATSAPP_FROM_AR'),
        ],
        'mx' => [
            'sid' => env('TWILIO_AUTH_SID_MX'),
            'token' => env('TWILIO_AUTH_TOKEN_MX'),
            'whatsapp_from' => env('TWILIO_WHATSAPP_FROM_MX'),
        ],
    ],

];
