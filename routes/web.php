<?php 

use Illuminate\Support\Facades\Route;
use Grhone\LaravelAffiliateSystem\Controllers\AffiliateController;
use Grhone\LaravelAffiliateSystem\Controllers\AdminController;
use Grhone\LaravelAffiliateSystem\Controllers\PaymentController;
use Grhone\LaravelAffiliateSystem\Controllers\PaypalWebhookController;

/*
|--------------------------------------------------------------------------
| Affiliate Routes
|--------------------------------------------------------------------------
*/
Route::prefix('affiliate')->name('affiliate.')->middleware(config('affiliate.middleware.admin', ['auth', 'affiliate']))->group(function () {
    Route::get('/dashboard', [AffiliateController::class, 'dashboard'])->name('dashboard');
    Route::get('/register', [AffiliateController::class, 'create'])->name('register');
    Route::post('/register', [AffiliateController::class, 'store'])->name('register.store');
    Route::get('/edit-profile', [AffiliateController::class, 'edit'])->name('edit');
    Route::put('/edit-profile', [AffiliateController::class, 'update'])->name('update');
    Route::get('/reports', [AffiliateController::class, 'reports'])->name('reports');
    Route::get('/reports/generate', [AffiliateController::class, 'generateReport'])->name('reports.generate');
    Route::get('/settings', [AffiliateController::class, 'settings'])->name('settings');
    Route::put('/settings', [AffiliateController::class, 'updateSettings'])->name('updateSettings');
    });

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(config('affiliate.middleware.affiliate', ['auth']))->group(function () {
    Route::get('/affiliate/dashboard', [AdminController::class, 'dashboard'])->name('affiliate.dashboard');
    Route::get('/manage-affiliates', [AdminController::class, 'manageAffiliates'])->name('manage_affiliates');
    Route::get('/affiliate/{id}', [AdminController::class, 'show'])->name('affiliate.show');
    Route::get('/affiliate/{id}/edit', [AdminController::class, 'edit'])->name('affiliate.edit');
    Route::put('/affiliate/{id}', [AdminController::class, 'update'])->name('affiliate.update');
    Route::delete('/affiliate/{id}', [AdminController::class, 'destroy'])->name('affiliate.destroy');    
    Route::post('/approve-affiliate/{id}', [AdminController::class, 'approveAffiliate'])->name('approve.affiliate');
    Route::post('/deny-affiliate/{id}', [AdminController::class, 'denyAffiliate'])->name('deny.affiliate');
    Route::get('/reports', [AdminController::class, 'reports'])->name('affiliates.reports');
    Route::get('/reports/generate', [AdminController::class, 'generateReport'])->name('affiliates.reports.generate');

    /*
    |--------------------------------------------------------------------------
    | Payment Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('payments')->name('payments.')->middleware(config('affiliate.middleware.payment'))->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::post('/process', [PaymentController::class, 'processAllPayments'])->name('process.all');
        // Additional payment-specific routes...
    });
});

/*
|--------------------------------------------------------------------------
| PayPal Webhook
|--------------------------------------------------------------------------
*/
Route::post('/api/paypal/webhook', [PaypalWebhookController::class , 'handle']);
