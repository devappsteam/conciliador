<?php

use App\Modules\Integrations\Acquirer\Http\Controllers\AcquirerController;
use Illuminate\Support\Facades\Route;

Route::get('acquirers/all', [AcquirerController::class, 'all']);
Route::apiResource('acquirers', AcquirerController::class)->parameters(['acquirers' => 'uuid']);
