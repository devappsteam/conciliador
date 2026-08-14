<?php

use App\Modules\ERP\Contract\Http\Controllers\ContractController;
use Illuminate\Support\Facades\Route;

Route::apiResource('contracts', ContractController::class)
    ->parameters(['contracts' => 'uuid']);
