<?php 

namespace Grhone\LaravelAffiliateSystem\Models;

use Illuminate\Database\Eloquent\Model;

class ReferredTransaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'referral_id',
        'purchase_amount',
        'type',
        'earnings',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'purchase_amount' => 'float',
        'earnings' => 'float',
    ];

    /**
     * Get the referral associated with the transaction.
     */
    public function referral()
    {
        return $this->belongsTo(Referral::class, 'referral_id');
    }
}
