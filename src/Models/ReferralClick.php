<?php

namespace Grhone\LaravelAffiliateSystem\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralClick extends Model
{
    protected $fillable = ['affiliate_id', 'ip', 'referring_url'];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }
}
