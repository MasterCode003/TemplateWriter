<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\RecipientController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group that
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Default user route (optional, remove if not needed)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Letters API Routes
Route::apiResource('letters', LetterController::class);

// Recipients API Routes
Route::apiResource('recipients', RecipientController::class);
