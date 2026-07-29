<?php

return [
    'enabled' => env('AUTH_MODULE_ENABLED', true),
    'pagination' => [
        'per_page' => env('AUTH_PER_PAGE', 15),
        'max_per_page' => env('AUTH_MAX_PER_PAGE', 100),
    ],
    'views' => [
        'namespace' => 'auth',
    ],
];
