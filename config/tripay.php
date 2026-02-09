<?php

return [
    'mode' => env('TRIPAY_MODE', 'sandbox'),
    'api_key' => env('TRIPAY_API_KEY'),
    'private_key' => env('TRIPAY_PRIVATE_KEY'),
    'merchant_code' => env('TRIPAY_MERCHANT_CODE'),
    
    'urls' => [
        'sandbox' => 'https://tripay.co.id/api-sandbox/',
        'production' => 'https://tripay.co.id/api/'
    ]
];