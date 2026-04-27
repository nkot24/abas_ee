<?php

return [
    // 'fake' for local testing, 'latvijas_pasts' for production
    'driver' => env('SHIPPING_DRIVER', 'latvijas_pasts'),

    // Latvijas Pasts EKIS API secret key (get from your account manager)
    'secret' => env('LATVIJAS_PASTS_SECRET', ''),

    'sender_name'     => env('LATVIJAS_PASTS_SENDER_NAME', ''),
    'sender_street'   => env('LATVIJAS_PASTS_SENDER_STREET', ''),
    'sender_house'    => env('LATVIJAS_PASTS_SENDER_HOUSE', ''),
    'sender_city'     => env('LATVIJAS_PASTS_SENDER_CITY', ''),
    'sender_postcode' => env('LATVIJAS_PASTS_SENDER_POSTCODE', ''),
];
