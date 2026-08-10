<?php

return [
    'enabled' => env('DEPARTMENT_MODULE_ENABLED', true),
    'pagination' => [
        'per_page' => env('DEPARTMENT_PER_PAGE', 15),
        'max_per_page' => env('DEPARTMENT_MAX_PER_PAGE', 100),
    ],
    'views' => [
        'namespace' => 'department',
    ],
];
