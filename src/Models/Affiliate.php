<?php

namespace Grhone\LaravelAffiliateSystem\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Affiliate extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'referral_code',
        'approved',
        'earnings',
        'first_name',
        'last_name',
        'website',
        'company_name',
        'street_name',
        'city',
        'country',
        'state',
        'zipcode',
        'phone_number',
        'vat_number',
        'minimum_payout',
        'commission_rate',
        'payout_method',
        'paypal_email'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'approved' => 'boolean',
        // Other casts as necessary
    ];

    // ... Rest of the methods remain unchanged

    /**
     * User relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * Referrals relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function referrals()
    {
        return $this->hasMany('Grhone\LaravelAffiliateSystem\Models\Referral', 'affiliate_id');
    }

    public function referredTransactions()
    {
        return $this->hasManyThrough(
            ReferredTransaction::class,
            Referral::class,
            'affiliate_id', // Foreign key on Referral table
            'referral_id',  // Foreign key on ReferredTransaction table
            'id',           // Local key on Affiliate table
            'id'            // Local key on Referral table
        );
    }


    /**
     * Payments relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany('Grhone\LaravelAffiliateSystem\Models\Payment', 'affiliate_id');
    }

    /**
     * Scope a query to only include approved affiliates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    public function settings()
    {
        return $this->hasMany(AffiliateSetting::class);
    }

    public function commissionRate()
    {
        return $this->commission_rate ?? config('affiliate.commissions.default_rate');
    }


    /**
     * Generate a unique referral code for the affiliate.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($affiliate) {
            // Fetch the referral code length from the configuration
            $referralCodeLength = config('affiliate.referral_code.length', 10); // Default to 10 if not set
            $affiliate->referral_code = Str::random($referralCodeLength);
        });
    }
}
