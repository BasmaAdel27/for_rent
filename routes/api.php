<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ResetPasswordController;
use App\Http\Controllers\API\AdvertisementController;
use App\Http\Controllers\Api\Advertisement_imageController;
use App\Http\Controllers\API\RateController;
use App\Http\Controllers\API\FavouriteController;
use App\Http\Controllers\API\admin\AdminAdvertisementController;
use App\Http\Controllers\api\OwnerprofileController;
use App\Http\Controllers\api\OwnerprofilesettinController;


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
    Route::get('/admin/pendingAdvertisement',[AdminAdvertisementController::class,'pendingRequest']);
    Route::get('/admin/acceptedAdvertisement',[AdminAdvertisementController::class,'acceptedRequest']);
    Route::get('/admin/declinedAdvertisement',[AdminAdvertisementController::class,'declinedRequest']);
    Route::delete('/admin/deleteAdvertisement/{advertisement_id}',[AdminAdvertisementController::class,'destroy']);
    Route::get('/admin/showAdvertisement/{advertisement_id}',[AdminAdvertisementController::class,'showRequest']);
    Route::put('/admin/confirmAdvertisement/{advertisement_id}',[AdminAdvertisementController::class,'confirmRequest']);
    Route::put('/admin/rejectedAdvertisement/{advertisement_id}',[AdminAdvertisementController::class,'rejectedRequest']);

});

Route::middleware(['renter','auth:api'])->group(function () {
    Route::get('/profile_setting',[OwnerprofilesettinController::class,'index']);
    Route::post('/profile_setting',[OwnerprofilesettinController::class,'update']);


});
Route::get('/owner_profile_setting',[OwnerprofilesettinController::class,'index']);

//middleware for owner
Route::middleware(['owner','auth:api'])->group(function () {
    Route::post('/advertisement',[AdvertisementController::class,'create']);
Route::post('/advertisement/{id}',[AdvertisementController::class,'update']);
Route::get('/notrented_advertisement',[AdvertisementController::class,'not_rented']);
Route::get('/rented_advertisement',[AdvertisementController::class,'rented']);
Route::get('/pending_advertisement',[AdvertisementController::class,'pending']);
Route::get('/accepted_advertisement',[AdvertisementController::class,'accepted']);
Route::post('/s_advertisement_image/{id}',[Advertisement_imageController::class,'store']);

Route::post('/u_advertisement_image/{id}',[Advertisement_imageController::class,'update']);
Route::get('/advertisement_image/{id}',[Advertisement_imageController::class,'destroy']);


Route::get('/declined_advertisement',[AdvertisementController::class,'declined']);
Route::get('/owner_profile',[OwnerprofileController::class,'index']);
Route::get('/profile_setting',[OwnerprofilesettinController::class,'index']);
Route::post('/profile_setting',[OwnerprofilesettinController::class,'update']);




});

//middleware for renter
Route::middleware(['renter','auth:api'])->group(function () {
    //rating
    Route::post('/rate/store/{advertisement_id}',[RateController::class,'store']);
    Route::get('/rate/delete/{advertisement_id}/{rate_id}',[RateController::class,'destroy']);
    //favourite
    Route::post('/addFavourite/{advertisement_id}',[FavouriteController::class,'store']);



});

Route::middleware(['auth:api'])->group(function () {
    Route::post('/logout', [AuthController::class,'logout']);

});


Route::post('/register',[AuthController::class,'register']);
Route::post('/verify',[AuthController::class,'verifyUser']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/password/email', [ResetPasswordController::class,'getResetToken']);
//Route::post('/refresh', 'refresh');

///////////////////////////////////Advertisement owner routes///////////////////////////////







Route::post('/password/reset', [ResetPasswordController::class,'reset']);



