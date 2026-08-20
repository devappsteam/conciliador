<?php

use App\Modules\Integrations\Brand\Http\Controllers\BrandController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('brands/all', [BrandController::class, 'all']);
    Route::patch('brands/{uuid}/toggle-status', [BrandController::class, 'toggleStatus']);
    Route::apiResource('brands', BrandController::class)
        ->parameters(['brands' => 'uuid']);
});
