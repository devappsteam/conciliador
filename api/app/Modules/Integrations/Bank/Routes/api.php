<?php

use App\Modules\Integrations\Bank\Http\Controllers\BankController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('banks/all', [BankController::class, 'all']);
    Route::patch('banks/{uuid}/toggle-status', [BankController::class, 'toggleStatus']);
    Route::apiResource('banks', BankController::class)->parameters(['banks' => 'uuid']);
});
