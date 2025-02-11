<?php

namespace Grhone\LaravelAffiliateSystem\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateSetting extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'affiliate_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'affiliate_id', 
        'key', 
        'value'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        // Casts can be defined based on the nature of the settings. 
        // Example: 'value' => 'array' if the value stores JSON data
    ];

    /**
     * Get the affiliate that owns the setting.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class, 'affiliate_id');
    }

}
