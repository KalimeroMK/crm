<?php

return [

    'CRM' => env('CRM_WSDL_PATH', storage_path('public/wsdl/crm-wsdl.xml')),

    'keys' => [
        'PRIVATE_KEY' => env('CRM_PRIVATE_KEY_PATH', storage_path('keys/private_key.pem')),
        'PUBLIC_CERT' => env('CRM_PUBLIC_CERT_PATH', storage_path('keys/public_cert.pem')),
        'PRODUCT_NAME' => env('CRM_PRODUCT_NAME', 'LEOSSCurrentView'),
        'AA_LISTING' => env('CRM_AA_LISTING', 'AAListing'),
    ],
];