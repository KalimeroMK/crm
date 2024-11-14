<?php

return [
    'wsdl' => [
        'crm' => env('CRM_ENV') === 'local' ? __DIR__.'/../wsdl/crm-wsdl.local.xml' : env('CRM_WSDL_URL', __DIR__.'/../wsdl/crm-wsdl.xml'),
    ],
    'keys' => [
        'private_key' => storage_path('keys/private_key.pem'),
        'public_cert' => storage_path('keys/public_cert.pem'),
        'product_name' => env('CRM_PRODUCT_NAME', 'Kalimero'),
        'aaListing' => env('CRM_AA_LISTING', 'AAListing'),
    ],
];