<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ResetPasswordController;
use App\Http\Controllers\API\AdvertisementController;
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
use App\Http\Controllers\API\admin\AdminTeamController;
use App\Http\Controllers\PaymentController;







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
    Route::post('/admin/about/update/{about_id}',[AdminAboutController::class,'update']);
    Route::delete('/admin/about/delete/{about_id}',[AdminAboutController::class,'destroy']);
    //follow_us
    Route::post('/admin/follow_us/update/{id}',[AdminFollowUsController::class,'update']);
    Route::get('/admin/contactUs/show/{contactus_id}',[AdminContactusController::class,'show']);
    Route::get('/admin/contactUs/list',[AdminContactusController::class,'index']);
    Route::delete('/admin/contactUs/delete/{contactus_id}',[AdminContactusController::class,'destroy']);




    Route::post('/admin/team/store',[AdminTeamController::class,'store']);
    Route::post('/admin/team/update/{team_id}',[AdminTeamController::class,'update']);
    Route::delete('/admin/team/destroy/{team_id}',[AdminTeamController::class,'destroy']);
    Route::get('/allRented/paymentmethod',[PaymentController::class,'paymentAdmin']);
    Route::get('/edit/team/{id}',[AdminTeamController::class,'edit']);
    Route::post('/update/image/{id}',[AdminTeamController::class,'updateImage']);
    Route::post('/update/follow/image/{id}',[AdminFollowUsController::class,'updateImage']);
    Route::post('/update/about/image/{id}',[AdminAboutController::class,'updateImage']);
    Route::get('/edit/about/{id}',[AdminAboutController::class,'edit']);
    Route::get('/edit/followUs/{id}',[AdminFollowUsController::class,'edit']);
    Route::get('/show/pending/{id}',[AdminAdvertisementController::class,'showPending']);


});
Route::get('/about/list',[AdminAboutController::class,'index']);
Route::get('/follow_us/list',[AdminFollowUsController::class,'index']);
Route::get('/teams/list',[AdminTeamController::class,'index']);


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
    Route::post('/paymentmethod',[PaymentController::class,'payment']);
    Route::get('/renter/advertisementrented',[PaymentController::class,'renterPayment']);
    Route::get('/renter/myfavourite',[FavouriteController::class,'index']);




});




//middleware for owner
Route::middleware(['owner','auth:api'])->group(function () {
    Route::post('/advertisement',[AdvertisementController::class,'create']);
Route::post('/advertisement/{id}',[AdvertisementController::class,'update']);
Route::delete('/advertisement/{id}',[AdvertisementController::class,'destroy']);

Route::get('/notrented_advertisement',[AdvertisementController::class,'not_rented']);
Route::get('/rented_advertisement',[AdvertisementController::class,'rented']);
Route::get('/pending_advertisement',[AdvertisementController::class,'pending']);
Route::get('/accepted_advertisement',[AdvertisementController::class,'accepted']);



Route::get('/declined_advertisement',[AdvertisementController::class,'declined']);
Route::get('/owner_profile',[OwnerprofileController::class,'index']);
Route::get('/profile_setting',[OwnerprofilesettinController::class,'index']);
Route::get('/cities',[CityController::class,'show']);
Route::get('/owner/advertisementrented',[PaymentController::class,'ownerPayment']);
Route::get('/edit/advertisement/{adver_id}',[AdvertisementController::class,'editAdvertisement']);
Route::post('/edit/image/{adver_id}/{img_id}',[AdvertisementController::class,'updateImage']);



});




Route::middleware(['auth:api'])->group(function () {
    Route::post('/logout', [AuthController::class,'logout']);
    Route::get('/get_notification', [GetNotificationController::class,'view']);
    Route::get('/profile_setting',[OwnerprofilesettinController::class,'index']);
    Route::get('/edit/image/{id}',[OwnerprofilesettinController::class,'editImage']);

    Route::post('/profile_setting',[OwnerprofilesettinController::class,'update']);
    Route::post('/profile_setting_name_phone',[OwnerprofilesettinController::class,'name_phone_setting']);
    Route::post('/profile_setting_password',[OwnerprofilesettinController::class,'update_password']);
    Route::post('/profile_setting_update_image/{id}',[OwnerprofilesettinController::class,'update_image']);








});


Route::get('/show/advertisement/{advertisement_id}',[AdvertisementController::class,'show']);
Route::post('/contactUs/store',[AdminContactusController::class,'store']);



Route::post('/register',[AuthController::class,'register']);
Route::post('/verify',[AuthController::class,'verifyUser']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/password/email', [ResetPasswordController::class,'getResetToken']);
Route::post('/password/verify', [ResetPasswordController::class,'verify']);
Route::get('/Home',[ HomeController::class,'index']);
Route::get('/search',[SearchController::class,'search']);
Route::get('/city_search',[SearchController::class,'city_choices']);
Route::get('/type_search',[SearchController::class,'type_choices']);
Route::get('/bedroom_search',[SearchController::class,'bedroom_choices']);

//city
    Route::post('/cities',[CityController::class,'store']);
    Route::get('/cities',[CityController::class,'show']);

//owner profile for guest
Route::get('/owner_profile_for_public/{id}',[OwnerprofileController::class,'owner_profile_for_public']);






//Route::post('/refresh', 'refresh');
Route::post('/password/reset', [ResetPasswordController::class,'reset']);







