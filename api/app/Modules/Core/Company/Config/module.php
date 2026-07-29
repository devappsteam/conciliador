<?php

return [
    'enabled' => env('COMPANY_MODULE_ENABLED', true),
    'pagination' => [
        'per_page' => env('COMPANY_PER_PAGE', 15),
        'max_per_page' => env('COMPANY_MAX_PER_PAGE', 100),
    ],
    'views' => [
        'namespace' => 'company',
    ],
];
