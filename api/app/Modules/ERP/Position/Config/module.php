<?php

return [
    'enabled' => env('POSITION_MODULE_ENABLED', true),
    'pagination' => [
        'per_page' => env('POSITION_PER_PAGE', 15),
        'max_per_page' => env('POSITION_MAX_PER_PAGE', 100),
    ],
    'views' => [
        'namespace' => 'position',
    ],
];
