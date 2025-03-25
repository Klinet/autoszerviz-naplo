<?php

use App\Domains\Car\Http\Controllers\CarController;
use App\Domains\Owner\Http\Controllers\OwnerController;
use App\Domains\ServiceLog\Http\Controllers\ServiceLogController;
use Illuminate\Support\Facades\Route;

Route::apiResource('cars', CarController::class);
Route::apiResource('owners', OwnerController::class);
Route::apiResource('servicelogs', ServiceLogController::class);
