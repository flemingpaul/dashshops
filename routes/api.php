<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponClicksController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CouponDownloadController;
use App\Http\Controllers\CouponRedeemController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\RetailerController;
use App\Http\Controllers\RatingsController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VipController;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\AppSettingsController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Auth
Route::post('/register', [AuthController::class, 'createUser']);
Route::post('/register/retailer', [AuthController::class, 'createRetailerUser']);
Route::post('/login', [AuthController::class, 'loginUser']);
Route::post('/login/retailer', [AuthController::class, 'loginRetailer']);
Route::post('/change-password', [AuthController::class, 'changePasswordAPI']);
Route::post('/forget-passwords', [ForgotPasswordController::class, 'forgetPasswordFormAPI']);
Route::post('/resets-password', [ForgotPasswordController::class, 'resetPasswordFormAPI']);
Route::post('/resets-password-firebase', [ForgotPasswordController::class, 'resetPasswordFirebase']);
Route::post('/verifyphonenumber', [AuthController::class, 'verifyPhoneNumber']);
Route::post('/validatephonenumber', [AuthController::class, 'validatePhoneAuth']);

//Notifications
Route::get('/testnotification/{user_id}', [NotificationsController::class, 'testNotificatiion']);

Route::get('/ratings/user/retailer/{user_id}/{retailer_id}', [RatingsController::class, 'getUserRetailerRating']);

Route::get('states/', [StateController::class, 'getAll'])->name('getAll');
Route::get('states-all/', [StateController::class, 'getAll2'])->name('getAll2');
Route::get('states/{id}', [StateController::class, 'getById'])->name('find');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/verifyToken', [AuthController::class, 'updateDeviceToken']);

    Route::get('/getnotifications/{user_id}', [NotificationsController::class, 'getNotifications']);
    Route::get('/markasread/{id}', [NotificationsController::class, 'markAsRead']);
    Route::get('/markallasread/{user_id}', [NotificationsController::class, 'markAllAsRead']);
    Route::get('/trashnotification/{id}', [NotificationsController::class, 'trashNotification']);
    Route::get('/getunreadnotificationcount/{user_id}', [NotificationsController::class, 'getUnreadCount']);

    //Category


    Route::post('/categories/add', [CategoryController::class, 'storeAPI']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    //Coupon
    Route::post('/coupons/add', [CouponController::class, 'storeAPI']);
    Route::put('/coupons/{id}', [CouponController::class, 'update']);
    Route::delete('/coupons/{id}', [CouponController::class, 'destroyAPI']);

    //CouponClicks
    Route::get('/clicks/retailer/{id}', [CouponClicksController::class, 'getByRetailer']);
    Route::put('/clicks/{id}', [CouponClicksController::class, 'update']);
    Route::delete('/clicks/{id}', [CouponClicksController::class, 'destroy']);

    //Ratings
    Route::get('/ratings/{approval_status}', [RatingsController::class, 'getAll']);
    Route::post('/ratings/update', [RatingsController::class, 'update']);
    Route::delete('/ratings/{id}', [RatingsController::class, 'destroy']);

    //Favorites
    Route::get('/favorites/{user_id}', [FavoriteController::class, 'getAll']);
    Route::get('/favorites/single/{user_id}/{retailer_id}', [FavoriteController::class, 'getUserFavoriteForStore']);
    Route::post('/favorites', [FavoriteController::class, 'create']);
    Route::delete('/favorites/{user_id}/{retailer_id}', [FavoriteController::class, 'destroy']);


    //CouponRedeemed
    Route::get('/redeems', [CouponRedeemController::class, 'getAll']);
    Route::get('/redeems/retailer/{id}', [CouponRedeemController::class, 'getByRetailer']);
    Route::get('/redeems/{id}', [CouponRedeemController::class, 'show']);
    Route::get('/redeems/download/{id}', [CouponRedeemController::class, 'findByDownloadId']);
    Route::post('/redeems/add', [CouponRedeemController::class, 'create']);
    Route::put('/redeems/{id}', [CouponRedeemController::class, 'update']);
    Route::delete('/redeems/{id}', [CouponRedeemController::class, 'destroy']);

    //CouponDownloads
    Route::get('/downloads', [CouponDownloadController::class, 'getAll']);
    Route::get('/downloads/user/{id}', [CouponDownloadController::class, 'getUserDownloadedCoupons']);
    Route::get('/downloads/retailer/{id}', [CouponDownloadController::class, 'getByRetailer']);
    Route::get('/downloads/{id}', [CouponDownloadController::class, 'show']);
    Route::get('/downloads/user/{qr_code}/{user_id}', [CouponDownloadController::class, 'getUserDownloadedCouponByQRCode']);
    Route::post('/downloads/add', [CouponDownloadController::class, 'create']);
    Route::put('/downloads/{id}', [CouponDownloadController::class, 'update']);
    Route::delete('/downloads/{id}', [CouponDownloadController::class, 'destroy']);

    //Users
    Route::group(['prefix' => '/user', 'as' => 'user.'], function () {
        Route::get('/users', [UserController::class, 'getAll'])->name('get-all');
        Route::get('/user', [UserController::class, 'getUser'])->name('find');
        Route::post('/user/add', [UserController::class, 'createAPI'])->name('create');
        Route::put('/{id}', [UserController::class, 'updateAPI'])->name('update');
        Route::delete('/user/{id}', [AuthController::class, 'deleteAccountAPI'])->name('delete');
    });

    //Retailers
    Route::group(['prefix' => '/retailers', 'as' => 'retailers.'], function () {

        Route::get('/analytics/summary/{retail_id}', [RetailerController::class, 'getAnalyticsSummary'])->name('getAnalyticsSummary');
        Route::put('/{id}', [RetailerController::class, 'update'])->name('update');
        Route::post('/update-banner', [RetailerController::class, 'updateBanner'])->name('update-banner');
        Route::delete('/{id}', [RetailerController::class, 'destroyAPI'])->name('delete');
    });

    Route::group(['prefix' => '/products', 'as' => 'products'], function () {
        Route::post('/update', [
            ProductController::class, 'update'
        ])->name('update-product');
        Route::delete('/{id}', [ProductController::class, 'delete'])->name('delete-product');
        Route::post('/addtocart', [ProductController::class, 'addtocart'])->name(
            'addtocart-product'
        );
        Route::delete('/removefromcart/{id}', [ProductController::class, 'removeFromCart'])->name('removefromcart-product');
    });
    //carts
    Route::post('/carts', [CartController::class, 'add']);
    Route::post('/carts/sync', [CartController::class, 'syncFromApp']);
    Route::delete('/carts/{id}', [ProductController::class, 'delete']);
    Route::put('/carts/{id}', [ProductController::class, 'update']);
    Route::get('/carts', [ProductController::class, 'getUserCart']);


    //State

    Route::post('states/add', [StateController::class, 'create'])->name('create');
    Route::put('states/{id}', [StateController::class, 'update'])->name('update');
    Route::delete('states/{id}', [StateController::class, 'destroy'])->name('delete');

    //Vip
    Route::get('vips/', [VipController::class, 'getAll'])->name('getAll');
    Route::get('vips/{id}', [VipController::class, 'getById'])->name('find');
    Route::get('vips/isvip/{id}', [VipController::class, 'isUserVip']);
    Route::post('vips/add', [VipController::class, 'create'])->name('create');
    Route::delete('vips/{id}', [VipController::class, 'destroy'])->name('delete');


    //AppSettings
    Route::get('/app-settings/{user_id}', [AppSettingsController::class, 'getUserSetting']);
    Route::put('/app-settings/{user_id}', [AppSettingsController::class, 'saveSettings']);
});

Route::post('/carts/products-from-vids', [CartController::class, 'getProductDetails']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::get('/categories/banner/{id}', [CategoryController::class, 'getCategoryBanner']);
//Ads
Route::get('/ads', [AdsController::class, 'getAllActive']);
Route::post('/register-ad-click', [AdsController::class, 'recordClick']);


//products
Route::group(['prefix' => '/products', 'as' => 'products'], function () {
    Route::get('/{id}', [ProductController::class, 'getProduct'])->name('get-product');
    Route::get('/variants/{product_id}', [ProductController::class, 'getProductVariants'])->name('get-product-variants');
    Route::get('/getrelevantproducts/{count}/{category?}/{search?}', [ProductController::class, 'getrelevantproducts']);
    Route::get('/getrelevantproductsbylocation/{count}/{category?}/{city?}/{state?}/{search?}', [ProductController::class, 'getrelevantproducts2']);
});

//ratings
Route::get('/ratings/retailer/{retailer_id}', [RatingsController::class, 'getRetailerRatings']);
Route::get('/ratings/retailersummary/{retailer_id}', [RatingsController::class, 'getRetailerRatingSummary']);


//Retailers
Route::group(['prefix' => '/retailers', 'as' => 'retailers.'], function () {
    Route::get('/getall/{count}/{page}/{category?}/{search?}', [RetailerController::class, 'getRetailers'])->name('index');
    Route::get('/getallbylocation/{count}/{page}/{category?}/{city?}/{state?}/{search?}', [RetailerController::class, 'getRetailersByLocation'])->name('index');
    Route::get('/getallbylocationIsland/{count}/{page}/{category?}/{island?}/{city?}/{state?}/{search?}', [RetailerController::class, 'getRetailersByLocationIsland'])->name('index');
    Route::get('/popular/{count?}/{page?}/{category?}/{city?}/{state?}/{search?}', [RetailerController::class, 'getPopularRetailers'])->name('index');
    Route::get('/popularbyisland/{count?}/{page?}/{category?}/{island?}/{city?}/{state?}/{search?}', [RetailerController::class, 'getPopularRetailersByIsland'])->name('index');
    Route::get('/single/{id}', [RetailerController::class, 'show'])->name('find');
    Route::get('/coupons/{id}/{type}/{search?}', [RetailerController::class, 'getCoupons'])->name('get_retailer_coupons');
    Route::get('/products/{id}/{type}/{search?}', [RetailerController::class, 'getProducts'])->name('get_retailer_products');
    Route::post('/add', [RetailerController::class, 'storeAPI'])->name('create');
    Route::get('getcities/{island}', [RetailerController::class, 'getCitiesApi'])->name('getcities');
    Route::get('getislandfrcity/{city}', [RetailerController::class, 'getIslandFrCityApi'])->name('getislandfrcity');
    Route::get('/getavailablestates', [RetailerController::class, 'getAllAvailableStates']);
});
//CouponClicks
Route::get('/clicks', [CouponClicksController::class, 'getAll']);
Route::get('/clicks/{id}', [CouponClicksController::class, 'show']);
Route::post('/clicks/add', [CouponClicksController::class, 'create']);

//Coupon
Route::get('/coupons', [CouponController::class, 'index']);
Route::get('/coupons/getall/{count}/{page}/{search?}', [CouponController::class, 'getAll']);
Route::get('/coupons/{id}', [CouponController::class, 'getCoupon']);
Route::get('/coupons/get-offer-type/{type}/{count}/{category?}/{search?}', [CouponController::class, 'getCouponOffer']);
Route::get('/coupons/get-offer-type-by-location/{type}/{count}/{category?}/{city?}/{state?}/{search?}', [CouponController::class, 'getCouponOffer2']);
Route::get('/coupons/get-offer-type-by-location-islands/{type}/{count}/{category?}/{island?}/{city?}/{state?}/{search?}', [CouponController::class, 'getCouponOffer3']);

Route::get('/coupons/states/{state}/{city}/{category?}/{search?}', [CouponController::class, 'getAllCouponsByState']);
Route::get('/coupons/states-island/{island}/{state?}/{city?}/{category?}/{search?}', [CouponController::class, 'getAllCouponsByStateIsland']);
Route::get('/coupons/zip-code/{zip_code}', [CouponController::class, 'getAllCouponsByZipCode']);
Route::get('/coupons/all-approved', [CouponController::class, 'getApproved']);
Route::get('/coupons/by-category/{id}/{type}/{count}/{page}/{search?}', [CouponController::class, 'getAllCouponsByCategory']);
