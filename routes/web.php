<?php 

use Illuminate\Support\Facades\Route;
use Grhone\LaravelAffiliateSystem\Controllers\AffiliateController;
use Grhone\LaravelAffiliateSystem\Controllers\AdminController;
use Grhone\LaravelAffiliateSystem\Controllers\PaymentController;
use Grhone\LaravelAffiliateSystem\Controllers\TrackingController;

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
    Route::post('/edit-profile', [AffiliateController::class, 'update'])->name('update');
    Route::get('/reports', [AffiliateController::class, 'reports'])->name('reports');
    Route::get('/settings/{id}', [AffiliateController::class, 'settings'])->name('settings');
    Route::post('/settings/{id}', [AffiliateController::class, 'updateSettings'])->name('updateSettings');
    });

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(config('affiliate.middleware.affiliate', ['auth']))->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/manage-affiliates', [AdminController::class, 'manageAffiliates'])->name('manage.affiliates');
    Route::post('/approve-affiliate/{id}', [AdminController::class, 'approveAffiliate'])->name('approve.affiliate');
    Route::post('/deny-affiliate/{id}', [AdminController::class, 'denyAffiliate'])->name('deny.affiliate');
    // Additional admin-specific routes...
});

/*
|--------------------------------------------------------------------------
| Payment Routes
|--------------------------------------------------------------------------
*/
Route::prefix('payments')->name('payments.')->middleware(config('affiliate.middleware.admin', ['auth']))->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('index');
    Route::post('/process', [PaymentController::class, 'processAllPayments'])->name('process.all');
    // Additional payment-specific routes...
});

/*
|--------------------------------------------------------------------------
| Tracking Routes
|--------------------------------------------------------------------------
*/
Route::prefix('tracking')->name('tracking.')->group(function () {
    Route::get('/track-click', [TrackingController::class, 'trackClick'])->name('track.click');
    Route::post('/track-conversion', [TrackingController::class, 'trackConversion'])->name('track.conversion');
    // Additional tracking-specific routes...
});
