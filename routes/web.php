<?php

use App\Http\Controllers\AdsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RetailerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Models\Category;
use App\Models\State;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.register', Category::all());
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/approval', [AuthController::class, 'approval'])->name('approval');
    Route::get('/denial', [AuthController::class, 'denial'])->name('denial');


    //    Route::middleware(['approved'])->group(function () {

    Route::get('/dashboard/analytics', [AuthController::class, 'analytics'])->name('analytics');
    Route::get('/dashboard/analytics-details', [AuthController::class, 'analyticsDetail'])->name('analytics-detail');
    //Users

    Route::post('/users/{id}', [UserController::class, 'update'])->name('update-user');
    Route::get('/edit-user', [UserController::class, 'showEditUser'])->name('edit-user');
    Route::get('/members', [UserController::class, 'showMembers'])->name('members');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password');


    Route::get('/profile/', [AuthController::class, 'fillProfile'])->name('profile');
    Route::put('/update-profile/retailer/{id}', [ProfileController::class, 'update'])->name('profile-update');
    //Route::get('dashboard/analytics', [AuthController::class, 'analytics'])->name('analytics');

    Route::get('/add-coupon', [CouponController::class, 'showAddCoupon'])->name('add-coupon');
    Route::post('/coupons/add', [CouponController::class, 'store'])->name('coupon.create');
    Route::get('/coupons', [CouponController::class, 'showAll'])->name('coupons');
    Route::get('/coupons/{id}', [CouponController::class, 'destroy'])->name('admin.coupons.delete');
    Route::get('/products', [ProductController::class, 'showAll'])->name('products');
    Route::get('/products/search', [ProductController::class, 'search'])->name('product-search');



    //Retailers
    Route::get('/retailers', [RetailerController::class, 'view'])->name('retailers');
    Route::get('/retailer/{id}', [RetailerController::class, 'showSingleRetailer'])->name('retailer');

    Route::put('/retailer/{id}/update-logo', [RetailerController::class, 'updateLogo'])->name('update-logo');

    Route::get('/delete-account', function () {
        return view('pages.confirm-account-delete');
    });
    Route::post('/delete-account', [AuthController::class, 'deleteAccount'])->name('delete-account');


    //    });
    //Ads

    Route::middleware(['admin'])->group(function () {

        Route::get('/retailers/add', [RetailerController::class, 'showAddRetailer'])->name('add-retailers');
        Route::post('/retailers/', [RetailerController::class, 'store'])->name('add-retailer');
    });
    Route::middleware(['super_admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::delete('/users/{user_id}', [UserController::class, 'destroy'])->name('admin.users.delete');
        Route::delete('/retailers/{retailer_id}', [RetailerController::class, 'destroy'])->name('admin.retailers.delete');
        Route::get('/users/{user_id}/approve', [UserController::class, 'approve'])->name('admin.users.approve');
        Route::get('/users/{user_id}/deny', [RetailerController::class, 'deny'])->name('admin.users.deny');
        Route::get('/users/{retailer_id}/deny', [RetailerController::class, 'deny'])->name('admin.retailers.deny');
        Route::get('/retailers/{retailer_id}/approve', [RetailerController::class, 'approve'])->name('admin.retailers.approve');
        Route::get('/coupons/{id}/{offer_type}/approve', [CouponController::class, 'approve'])->name('admin.coupons.approve');
        Route::get('/coupons/{id}/deny', [CouponController::class, 'deny'])->name('admin.coupons.deny');

        Route::post('/user/add', [UserController::class, 'create'])->name('user.create');
        Route::get('/add-user', [UserController::class, 'showAddUser'])->name('add-user');
        Route::get('/admin-portal', [UserController::class, 'showAdminPortal'])->name('admin-portal');


        //Ads
        Route::get('/ads', [AdsController::class, 'showAll'])->name('ads');
        Route::get('/ads/get/{id}', [AdsController::class, 'getById']);
        Route::post('/ads/update', [AdsController::class, 'update'])->name('update-ads');
        Route::delete('/ads/{id}', [AdsController::class, 'destroy'])->name('delete-ad');

        //Categories
        Route::get('/categories', [CategoryController::class, 'showAll'])->name('categories');
        Route::post('/categories/add', [CategoryController::class, 'store'])->name('add-category');
        Route::put('/categories/edit/{id}', [CategoryController::class, 'update'])->name('edit-category');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroyWeb'])->name('delete-category');
    });
});
Route::get('login', [AuthController::class, 'index'])->name('login');

Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');


Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');

Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');

Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');

Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('/create-retailer', [RetailerController::class, 'store'])->name('create-retailer');

Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');

Route::post('post-creation', [RetailerController::class, 'postCreation'])->name('retailer.post');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('terms-of-use', function () {
    return view('terms');
})->name('terms');

Route::get('account-deleted', function () {
    return view('account-deleted');
})->name('account-deleted');


Route::resource('files', FileUploadController::class);
