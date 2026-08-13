<?php

return [
    'enabled' => env('ACQUIRER_MODULE_ENABLED', true),
    'pagination' => [
        'per_page' => env('ACQUIRER_PER_PAGE', 15),
        'max_per_page' => env('ACQUIRER_MAX_PER_PAGE', 100),
    ],
    'views' => [
        'namespace' => 'acquirer',
    ],
];
