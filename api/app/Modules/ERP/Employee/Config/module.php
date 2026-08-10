<?php

return [
    'enabled' => env('EMPLOYEE_MODULE_ENABLED', true),
    'pagination' => [
        'per_page' => env('EMPLOYEE_PER_PAGE', 15),
        'max_per_page' => env('EMPLOYEE_MAX_PER_PAGE', 100),
    ],
    'views' => [
        'namespace' => 'employee',
    ],
];
