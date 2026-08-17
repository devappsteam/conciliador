<?php

use App\Modules\Reconciliation\AcquirerConfig\Http\Controllers\AcquirerConfigController;
use Illuminate\Support\Facades\Route;

Route::apiResource('acquirer-configs', AcquirerConfigController::class)
    ->parameters(['acquirer-configs' => 'uuid']);
