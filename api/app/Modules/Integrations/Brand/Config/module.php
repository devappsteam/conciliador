<?php

return [
    'enabled' => env('BRAND_MODULE_ENABLED', true),
    'pagination' => [
        'per_page' => env('BRAND_PER_PAGE', 15),
        'max_per_page' => env('BRAND_MAX_PER_PAGE', 100),
    ],
    'views' => [
        'namespace' => 'brand',
    ],
];
