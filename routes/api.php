<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ResetPasswordController;
use App\Http\Controllers\API\AdvertisementController;
use App\Http\Controllers\Api\Advertisement_imageController;


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

Route::post('/register',[AuthController::class,'register']);
Route::post('/verify',[AuthController::class,'verifyUser']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/logout', [AuthController::class,'logout']);
Route::post('/password/email', [ResetPasswordController::class,'getResetToken']);
//Route::post('/refresh', 'refresh');
///////////////////////////////////Advertisement owner routes///////////////////////////////
Route::post('/advertisement',[AdvertisementController::class,'create']);
Route::post('/advertisement/{id}',[AdvertisementController::class,'update']);
Route::post('/advertisement_image',[Advertisement_imageController::class,'store']);

Route::get('/advertisement_image/{id}',[Advertisement_imageController::class,'destroy']);
Route::get('/notrented_advertisement',[AdvertisementController::class,'not_rented']);
Route::get('/rented_advertisement',[AdvertisementController::class,'rented']);
Route::get('/pending_advertisement',[AdvertisementController::class,'pending']);
Route::get('/accepted_advertisement',[AdvertisementController::class,'accepted']);

Route::get('/declined_advertisement',[AdvertisementController::class,'declined']);








