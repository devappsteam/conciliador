<?php

use App\Modules\Integrations\AcquirerTransaction\Http\Controllers\AcquirerTransactionController;
use Illuminate\Support\Facades\Route;

Route::apiResource('acquirer-transactions', AcquirerTransactionController::class)
    ->parameters(['acquirer-transactions' => 'uuid']);
