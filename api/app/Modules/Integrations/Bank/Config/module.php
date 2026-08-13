<?php

return [
    'enabled' => env('BANK_MODULE_ENABLED', true),
    'pagination' => [
        'per_page' => env('BANK_PER_PAGE', 15),
        'max_per_page' => env('BANK_MAX_PER_PAGE', 100),
    ],
    'views' => [
        'namespace' => 'bank',
    ],
];
