<?php

use App\Livewire\Aboutus;
use App\Models\Contactus;
use App\Models\EmailType;
use App\Livewire\AdminLive;
use App\Livewire\CardsLive;
use App\Livewire\SlideLive;
use App\Livewire\UsersLive;
use App\Livewire\EmailsLive;
use App\Livewire\GroupsLive;
use App\Models\CompanyEmail;
use App\Livewire\CompanyLive;
use App\Livewire\ProductLive;
use App\Livewire\ProjectLive;
use App\Livewire\SerialsLive;
use App\Livewire\ServiceLive;
use App\Livewire\SettingLive;
use App\Livewire\CategoryLive;
use App\Livewire\ContactusLive;
use App\Livewire\EmailTypeLive;
use App\Livewire\PromoCodeLive;
use App\Livewire\ProductTypeLive;
use App\Livewire\TestimonialLive;
use App\Livewire\NotificationLive;
use App\Livewire\PaymentMethodsLive;
use App\Livewire\Sidepages\TermsLive;
use App\Livewire\Sidepages\TermsAdminLive;

use Illuminate\Support\Facades\Route;
use App\Livewire\Locations\RegionsLive;
use App\Livewire\Sidepages\PrivacyLive;
use App\Livewire\Sidepages\PrivacyAdminLive;
use Illuminate\Support\Facades\Artisan;
use App\Livewire\Locations\CountriesLive;
use App\Livewire\Service\ServiceCreateLive;
use App\Livewire\Service\ServiceUpdateLive;
use App\Http\Controllers\Dash\HomeController;
use App\Http\Controllers\Dash\AdminLoginController;
use App\Http\Controllers\Dash\SettingController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsCompany;
use App\Livewire\CompaniesLive;
use App\Livewire\CompaniesOrdersLive;
use App\Livewire\Company\CompanyServiceLive;
use App\Livewire\Company\OrdersLive;
use App\Livewire\Company\WorktimesLive;
use App\Livewire\SocialLive;
use App\Livewire\StandardLive;
use App\Models\Standard;

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

Route::as('dashboard.')->group(function(){

    Route::middleware('auth:admin')->group(function(){
        Route::get('notifications', NotificationLive::class)->name('notifications');

        Route::middleware(IsAdmin::class)->group(function(){
            Route::get('/', [HomeController::class, 'index'])->name('home');
            Route::get('/services', ServiceLive::class)->name('services');
            Route::get('standards', StandardLive::class)->name('standards');
            Route::get('companies', CompaniesLive::class)->name('companies');
    
            Route::get('admins', AdminLive::class)->name('admins');
            Route::get('contact-messages', ContactusLive::class)->name('contacts');
            Route::get('slides', SlideLive::class)->name('slider-images');
            Route::get('aboutus', Aboutus::class)->name('aboutus');
            Route::get('social_links', SocialLive::class)->name('social_links');
            Route::get('public-setting', [SettingController::class, 'index'])->name('public_setting');
            Route::post('public-setting', [SettingController::class, 'store'])->name('public_setting_post');
            Route::get('terms', TermsLive::class)->name('terms');
            Route::get('termsAdmin', TermsAdminLive::class)->name('termsAdmin');

            Route::get('privacy', PrivacyLive::class)->name('privacy');
            Route::get('privacyAdmin', PrivacyAdminLive::class)->name('privacyAdmin');

            Route::get('payment_methods', PaymentMethodsLive::class)->name('payment_methods');
            Route::get('users', UsersLive::class)->name('users');  
            
            Route::get('/companies_orders', CompaniesOrdersLive::class)->name('orders');
        });

        Route::middleware(IsCompany::class)->prefix('company')->as('company.')->group(function(){
            Route::get('/', [HomeController::class, 'index'])->name('home');
            Route::get('/services', CompanyServiceLive::class)->name('services');
            Route::get('/worktimes', WorktimesLive::class)->name('worktimes');
            Route::get('/orders', OrdersLive::class)->name('orders');
        });
        Route::get('order_show/{order_id}', [HomeController::class, 'order_show'])->name('order_show');
        Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');
    });

    Route::get('login', [AdminLoginController::class, 'index'])->name('login');
    Route::post('login', [AdminLoginController::class, 'login'])->name('login.post');
});



Route::get('language/{locale}', function($locale){
    if (isset($locale) && in_array($locale, ['ar','en'])) {
        app()->setLocale($locale);
        session()->put('locale', $locale);
    }

    return redirect()->back();
})->name('lang');

Route::any('artisan/{command}', function($command){
    Artisan::call($command);
    dd(Artisan::output());

})->name('artisan');
