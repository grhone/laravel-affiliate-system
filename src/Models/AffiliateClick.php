<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateClick extends Model
{
    protected $fillable = ['affiliate_id', 'ip', 'referring_url'];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }
}
