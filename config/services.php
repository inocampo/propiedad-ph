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
/* Agregado para Cartera */
    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_AUTH_TOKEN'),
        'whatsapp_number' => env('TWILIO_WHATSAPP_NUMBER'),
    ],
    
    'wallet' => [
        'invoice_prefix' => env('INVOICE_PREFIX', 'INV'),
        'default_due_days' => env('DEFAULT_DUE_DAYS', 15),
        'late_fee_days' => env('LATE_FEE_DAYS', 30),
        'late_fee_percentage' => env('LATE_FEE_PERCENTAGE', 5),
        'admin_fee_amount' => env('ADMIN_FEE_AMOUNT', 150000),
    ],
/* Fin agregado para Cartera */
];
