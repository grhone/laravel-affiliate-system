<?php

namespace Grhone\LaravelAffiliateSystem\Traits;

use Grhone\LaravelAffiliateSystem\Models\Affiliate;
use Grhone\LaravelAffiliateSystem\Models\Referral;
use Laravel\Cashier\Events\SubscriptionCreated;
use Laravel\Cashier\Events\SubscriptionRenewed;

trait Referrable
{
    /**
     * Boot the Referrable trait for the model.
     */
    protected static function bootReferrable()
    {
        static::created(function ($model) {
            if ($referralCode = request()->cookie('affiliate_referral')) {
                // Logic to associate the model with the affiliate who referred it
                $affiliate = Affiliate::where('referral_code', $referralCode)->first();

                if ($affiliate) {
                    $referral = new Referral();
                    $referral->affiliate_id = $affiliate->id;
                    $referral->referred_user_id = $model->id;

                    // Check if a click_id cookie exists and associate the referral with the click
                    if ($clickId = request()->cookie('click_id')) {
                        $referral->click_id = $clickId;
                    }

                    $referral->save();

                    // Additional logic like updating affiliate earnings, sending notifications, etc.
                }
            }
        });

    }

    /**
     * Affiliate relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function referredBy()
    {
        return $this->belongsTo(Affiliate::class, 'affiliate_id');
    }

    /**
     * Referrals relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referred_user_id');
    }

    /**
     * Get the affiliate account of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function affiliateAccount()
    {
        return $this->belongsTo(Affiliate::class, 'id', 'user_id');
    }

    /**
     * Check if the user has a affiliate account.
     *
     * @return bool
     */
    public function hasAffiliateAccount()
    {
        return !is_null($this->affiliateAccount);
    }
}
