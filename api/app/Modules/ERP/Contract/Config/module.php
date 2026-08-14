<?php

return [
    'enabled' => env('CONTRACT_MODULE_ENABLED', true),
    'pagination' => [
        'per_page' => env('CONTRACT_PER_PAGE', 15),
        'max_per_page' => env('CONTRACT_MAX_PER_PAGE', 100),
    ],
    'views' => [
        'namespace' => 'contract',
    ],
];
