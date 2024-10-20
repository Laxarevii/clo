<?php

use App\Http\Controllers\CloakController;
use App\Http\Controllers\CloakController2;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('resolve', [CloakController::class, 'resolve']);
Route::get('resolve2', [CloakController2::class, 'resolve']);
