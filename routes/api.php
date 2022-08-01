<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ResetPasswordController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//middleware for admin
Route::middleware(['Admin','auth:api'])->group(function () {
});

//middleware for owner
Route::middleware(['owner','auth:api'])->group(function () {

});

//middleware for renter
Route::middleware(['renter','auth:api'])->group(function () {
    Route::get('/', function () {
        return auth()->user();
    });
});

Route::middleware(['auth:api'])->group(function () {
    Route::post('/logout', [AuthController::class,'logout']);

});


Route::post('/register',[AuthController::class,'register']);
Route::post('/verify',[AuthController::class,'verifyUser']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/password/email', [ResetPasswordController::class,'getResetToken']);
Route::post('/password/reset', [ResetPasswordController::class,'reset']);



