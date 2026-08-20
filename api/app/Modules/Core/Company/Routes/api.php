<?php

use App\Modules\Core\Company\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/companies/all', [CompanyController::class, 'all']);
    Route::apiResource('companies', CompanyController::class)->parameters(['companies' => 'uuid']);
});
