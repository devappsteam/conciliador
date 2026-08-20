<?php

use App\Modules\Integrations\Acquirer\Http\Controllers\AcquirerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('acquirers/all', [AcquirerController::class, 'all']);
    Route::patch('acquirers/{uuid}/toggle-status', [AcquirerController::class, 'toggleStatus']);
    Route::apiResource('acquirers', AcquirerController::class)->parameters(['acquirers' => 'uuid']);
});
