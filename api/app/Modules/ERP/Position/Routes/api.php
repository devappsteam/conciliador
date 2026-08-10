<?php

use App\Modules\ERP\Position\Http\Controllers\PositionController;
use Illuminate\Support\Facades\Route;

Route::prefix('positions')->group(function () {
    Route::get('all', [PositionController::class, 'all']);
});
Route::apiResource('positions', PositionController::class)->parameters(['positions' => 'uuid']);
