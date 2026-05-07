<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Configuration
    |--------------------------------------------------------------------------
    | Used by FirebaseSyncService to connect to Firestore via REST API.
    | Set these values in your .env file.
    */

    'project_id' => env('FIREBASE_PROJECT_ID', 'cryptolibrarycenter'),

    'api_key'    => env('FIREBASE_API_KEY', 'AIzaSyD2wRZwsB3Nb_K5XLSfLXKn-LSM7dICMQY'),

    // Optional: protect the /sync/firebase webhook with a secret token
    // Set FIREBASE_WEBHOOK_SECRET in .env and send it as X-Sync-Secret header
    'webhook_secret' => env('FIREBASE_WEBHOOK_SECRET', null),
];
