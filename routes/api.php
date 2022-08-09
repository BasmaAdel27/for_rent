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
use App\Http\Controllers\api\GetNotificationController;

use App\Http\Controllers\API\admin\AdminAboutController;
use App\Http\Controllers\API\admin\AdminFollowUsController;
use App\Http\Controllers\API\admin\AdminUsersController;
use App\Http\Controllers\API\admin\AdminContactusController;
use App\Http\Controllers\api\CityController;
use App\Http\Controllers\api\HomeController;
use App\Http\Controllers\api\SearchController;







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

//middleware for admin & super admin
Route::middleware(['checkRole:admin,superAdmin','auth:api'])->group(function () {
    //advertisements
    Route::get('/admin/pendingAdvertisement',[AdminAdvertisementController::class,'pendingRequest']);
    Route::get('/admin/acceptedAdvertisement',[AdminAdvertisementController::class,'acceptedRequest']);
    Route::get('/admin/declinedAdvertisement',[AdminAdvertisementController::class,'declinedRequest']);
    Route::delete('/admin/deleteAdvertisement/{advertisement_id}',[AdminAdvertisementController::class,'destroy']);
    Route::get('/admin/showAdvertisement/{advertisement_id}',[AdminAdvertisementController::class,'showRequest']);
    Route::put('/admin/confirmAdvertisement/{advertisement_id}',[AdminAdvertisementController::class,'confirmRequest']);
    Route::put('/admin/rejectedAdvertisement/{advertisement_id}',[AdminAdvertisementController::class,'rejectedRequest']);
   //about
    Route::post('/admin/about/store',[AdminAboutController::class,'store']);
    Route::get('/admin/about/list',[AdminAboutController::class,'index']);
    Route::post('/admin/about/update/{about_id}',[AdminAboutController::class,'update']);
    Route::delete('/admin/about/delete/{about_id}',[AdminAboutController::class,'destroy']);
    //follow_us
    Route::get('/admin/follow_us/list',[AdminFollowUsController::class,'index']);
    Route::post('/admin/follow_us/update/{id}',[AdminFollowUsController::class,'update']);
    Route::get('/admin/contactUs/show/{contactus_id}',[AdminContactusController::class,'show']);
    Route::get('/admin/contactUs/list',[AdminContactusController::class,'index']);
    Route::get('/admin/contactUs/delete/{contactus_id}',[AdminContactusController::class,'destroy']);
    Route::post('/cities',[CityController::class,'store']);

});

//middleware for super admin only
Route::middleware(['SuperAdmin','auth:api'])->group(function () {
    //users control
    Route::get('/admin/renters',[AdminUsersController::class,'AllRenters']);
    Route::get('/admin/owners',[AdminUsersController::class,'AllOwners']);
    Route::get('/admin/admins',[AdminUsersController::class,'AllAdmins']);
    Route::get('/admin/blocks',[AdminUsersController::class,'AllBlocks']);
    Route::delete('/admin/delete/{userId}',[AdminUsersController::class,'destroy']);
    Route::put('/admin/block/{userId}',[AdminUsersController::class,'block']);
    Route::post('/admin/addAdmin',[AdminUsersController::class,'addAdmin']);

});




//middleware for renter
Route::middleware(['renter','auth:api'])->group(function () {
    //rating
    Route::post('/rate/store/{advertisement_id}',[RateController::class,'store']);
    Route::get('/rate/delete/{advertisement_id}/{rate_id}',[RateController::class,'destroy']);
    //favourite
    Route::post('/addFavourite/{advertisement_id}',[FavouriteController::class,'store']);
    Route::get('/profile_setting',[OwnerprofilesettinController::class,'index']);
    Route::post('/profile_setting',[OwnerprofilesettinController::class,'update']);





});




//middleware for owner
Route::middleware(['owner','auth:api'])->group(function () {
    Route::post('/advertisement',[AdvertisementController::class,'create']);
Route::post('/advertisement/{id}',[AdvertisementController::class,'update']);
Route::get('/advertisement/{id}',[AdvertisementController::class,'destroy']);

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




Route::middleware(['auth:api'])->group(function () {
    Route::post('/logout', [AuthController::class,'logout']);
    Route::get('/get_notification', [GetNotificationController::class,'view']);




});


Route::get('/show/advertisement/{advertisement_id}',[AdvertisementController::class,'show']);
Route::post('/contactUs/store',[AdminContactusController::class,'store']);



Route::post('/register',[AuthController::class,'register']);
Route::post('/verify',[AuthController::class,'verifyUser']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/password/email', [ResetPasswordController::class,'getResetToken']);
Route::get('/Home',[ HomeController::class,'index']);
Route::get('/search',[SearchController::class,'search']);



//Route::post('/refresh', 'refresh');
Route::post('/password/reset', [ResetPasswordController::class,'reset']);







