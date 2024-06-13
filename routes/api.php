<?php

use App\Functions\PushNotification;
use App\Models\Card;
use App\Models\Serial;
use Illuminate\Http\Request;
use App\Functions\ResponseHelper;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TapController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\AuthUserController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\FavorateController;
use App\Http\Controllers\Api\CompanyServicesController;
use App\Http\Controllers\Api\CompanyWorkTimesController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\WorktimeController;
use App\Models\Setting;

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
Route::get('countries', [CountryController::class, 'index']);

//Authentication
Route::get('user_information', [AuthUserController::class, 'userInformation']);
Route::post('login', [AuthUserController::class, 'login']);
Route::post('registration', [AuthUserController::class, 'registration']);
Route::post('send_whatsapp_otp', [AuthUserController::class, 'sendWhatsappOtp']);
Route::post('forget_password', [AuthUserController::class, 'forgetPassword']);
Route::post('reset_password', [AuthUserController::class, 'resetPassword']);
Route::post('change_password', [AuthUserController::class, 'changePassword']);
Route::middleware('auth:sanctum')->group(function(){
    Route::post('user_verified', [AuthUserController::class, 'userVerified'])->middleware('auth:sanctum');
    Route::post('delete_account', [AuthUserController::class, 'deleteMyAccount'])->middleware('auth:sanctum');
    Route::post('logout', [AuthUserController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('update_user', [AuthUserController::class, 'updateUser'])->middleware('auth:sanctum');
    Route::post('get_times', [WorktimeController::class, 'get_times']);
});

Route::get('home', [HomeController::class, 'index']);
Route::post('contact-us', [ContactController::class, 'store']);
Route::get('terms', [HomeController::class, 'terms']);
Route::get('privacy', [HomeController::class, 'privacy']);
Route::get('about_us', [HomeController::class, 'about']);
Route::get('links', [HomeController::class, 'social_links']);

Route::get('services', [ServiceController::class, 'index']);
Route::get('service/{id}', [ServiceController::class, 'show']);
Route::get('company_service/{id}', [ServiceController::class, 'CompanyServiceShow']);

Route::get('reviews/{company_id}', [ReviewController::class, 'index']);
Route::post('search', [SearchController::class, 'index']);

    Route::get('user_orders/current', [OrderController::class, 'current_user_orders']);
    Route::get('user_orders/previous', [OrderController::class, 'previous_user_orders']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('review', [ReviewController::class, 'store']);
    Route::get('review/user_review/{company_id}', [ReviewController::class, 'show']);
    Route::post('review/update', [ReviewController::class, 'update']);

    Route::get('favorites', [FavorateController::class, 'index']);
    Route::post('favorites/add_remove', [FavorateController::class, 'storeOrRemove']);

    Route::get('regions', [AddressController::class, 'getRegions']);
    Route::post('address', [AddressController::class, 'storeAddress']);
    Route::get('get_addresses', [AddressController::class, 'getUserAddress']);
    Route::post('address/delete/{address_id}', [AddressController::class, 'delete']);
    Route::post('address/active_address/{address_id}', [AddressController::class, 'active_address']);

    Route::get('cart', [CartController::class, 'getCart']);
    Route::post('cart/add', [CartController::class, 'add']);
    Route::post('cart/delete/{cart_id}', [CartController::class, 'delete']);
    Route::post('cart/delete_all', [CartController::class, 'delete_all']);
    Route::get('other_services', [CartController::class, 'other_services']);

    Route::get('payment_methods', [OrderController::class, 'payment_methods']);
    Route::post('order', [OrderController::class, 'store']);
    Route::post('order_change_status/{order_id}', [OrderController::class, 'changeStatus']);
    Route::get('user_orders/details/{order_id}', [OrderController::class, 'order_details']);


    Route::get('user_notification', [NotificationController::class, 'index']);
    Route::get('admin_notification', [NotificationController::class, 'indexAdmin']);

    Route::apiResource('company_services', CompanyServicesController::class);
    Route::apiResource('company_worktimes', CompanyWorkTimesController::class);


});

//payment
Route::group(['prefix' => 'payment','as' => 'payment.'], function () {
    Route::group(['prefix' => 'tap','as' => 'tap.'], function () {
        Route::any('init', [TapController::class,'init'])->name('init'); // client.payment.tap.init
//            Route::any('response', [TapController::class,'response'])->name('response'); // client.payment.tap.response
    });
});
Route::get('tap_response', [TapController::class, 'response'])->name('tap_response');
Route::get('tap/check_result', [TapController::class, 'check_result'])->name('check_result');



Route::post('version_checker', [HomeController::class, 'version_checker']);

Route::post('company_login', [CompanyController::class, 'login']);
Route::post('company_logout', [CompanyController::class, 'logout']);
Route::post('company_registeration', [CompanyController::class, 'register']);
Route::post('appLanguage', [HomeController::class, 'appLanguage'])->middleware(['auth:sanctum']);

// /////////////////////////////
// Route::get('group/{group_id}', [HomeController::class, 'group']);
// Route::get('categories', [HomeController::class, 'categories']);
// Route::get('categories/{category_id}/sub_categories', [HomeController::class, 'subCategories']);
// Route::get('sub_categories/{sub_category_id}/cards', [HomeController::class, 'subCategory']);
// Route::get('category_title/{cartegory_id}', [HomeController::class, 'category_title']);
// Route::get('card/{card_id}', [HomeController::class, 'show_card']);
// // Route::get('search', [HomeController::class, 'search']);
// Route::get('about_us', [HomeController::class, 'about']);

// Route::middleware('auth:sanctum')->group(function(){
//     Route::get('cart', [CartController::class, 'index']);
//     Route::post('cart/add_item', [CartController::class, 'addItem']);
//     Route::post('cart/update_item', [CartController::class, 'updateItem']);
//     Route::post('cart/remove_item', [CartController::class, 'removeItem']);
//     Route::get('cart/quantity', [CartController::class, 'get_quantity']);

//     Route::get('payment_methods', [OrderController::class, 'payment_methods']);
//     Route::post('use_coupone', [OrderController::class, 'use_coupone']);
//     Route::post('order', [OrderController::class, 'store']);

//     Route::get('old_orders', [ProfileController::class, 'old_orders']);
//     Route::get('old_order_details/{order_id}', [ProfileController::class, 'old_order_details']);
// });


// Route::get('tap_response', [TapController::class, 'response'])->name('tap_response');
// Route::get('tap/check_result', [TapController::class, 'check_result'])->name('check_result');

// Route::post('contact_us', [HomeController::class, 'contactUs']);

// Route::get('footer', [HomeController::class, 'footer']);
// Route::get('contact-us', [ContactController::class, 'index']);
// Route::post('contact-us', [ContactController::class, 'store']);

// Route::post('version_checker', [HomeController::class, 'version_checker']);

// Route::get('more', [HomeController::class, 'more']);

Route::get('currency', function(){
    $data = [
        'currency' => "BD"
    ];
    return ResponseHelper::make($data);
});

// Route::get('about-us', [HomeController::class, 'about_us']);

// Route::get('products', [ProductController::class, 'index']);

// Route::post('order', [OrderController::class, 'store']);
