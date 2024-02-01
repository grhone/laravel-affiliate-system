<?php

namespace Grhone\LaravelAffiliateSystem\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Grhone\LaravelAffiliateSystem\Models\ReferredTransaction; 

class Referral extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'affiliate_id', 'referred_user_id', 'conversion', 'earnings', 'referred_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['referred_at'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'conversion' => 'boolean',
        // Other casts as necessary
    ];

    /**
     * Affiliate relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function affiliate()
    {
        return $this->belongsTo('Grhone\LaravelAffiliateSystem\Models\Affiliate', 'affiliate_id');
    }

    public function click()
    {
        return $this->belongsTo(AffiliateClick::class);
    }


    /**
     * Referred user relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function referredUser()
    {
        return $this->belongsTo('App\Models\User', 'referred_user_id');
    }

    /**
     * Scope a query to only include conversions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeConversions($query)
    {
        return $query->where('conversion', true);
    }

    /**
     * Referred transactions relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function referredTransactions()
    {
        return $this->hasMany(ReferredTransaction::class, 'referral_id');
    }
}
