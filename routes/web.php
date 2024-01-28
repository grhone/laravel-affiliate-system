<?php

use Illuminate\Support\Facades\Route;
use Grhone\LaravelAffiliateSystem\Controllers\ReferralController;


Route::middleware('web')->group(function () {

    Route::get('generate-ref-accounts', [ReferralController::class, 'createReferralCodeForExistingUsers'])
        ->name('generateReferralCodes');
});
