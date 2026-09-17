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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'razorpay' => [
        'key'    => env('RAZORPAY_KEY_ID'),
        'secret' => env('RAZORPAY_KEY_SECRET'),
            'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET'),

    ],
    'facebook' => [
    'client_id' => env('FACEBOOK_APP_ID'),
    'client_secret' => env('FACEBOOK_APP_SECRET'),
    'redirect' => env('FACEBOOK_REDIRECT_URI'),
    'graph_version' => env('FACEBOOK_GRAPH_VERSION', 'v23.0'),
],

    'firebase' => [

    'project_id' => env('FIREBASE_PROJECT_ID'),

    'credentials' => storage_path(
        'app/firebase/firebase-service-account.json'
    ),

    'vapid_key' => env('FIREBASE_VAPID_KEY'),

],
'amazon' => [

    'client_id' => env('SP_API_CLIENT_ID'),

    'client_secret' => env('SP_API_CLIENT_SECRET'),

    'refresh_token' => env('SP_API_REFRESH_TOKEN'),

    'marketplace_id' => env('SP_API_MARKETPLACE_ID'),

    'seller_id' => env('SP_API_SELLER_ID'),

],
'shiprocket' => [
    'base_url' => env('SHIPROCKET_BASE_URL'),
    'email' => env('SHIPROCKET_EMAIL'),
    'password' => env('SHIPROCKET_PASSWORD'),
    'pickup_pincode' => env('SHIPROCKET_PICKUP_PINCODE'),
    'default_weight' => env('SHIPROCKET_DEFAULT_WEIGHT', 0.5),
],
];
