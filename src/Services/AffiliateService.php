<?php

namespace Grhone\LaravelAffiliateSystem\Services;

use Grhone\LaravelAffiliateSystem\Models\Affiliate;
use Grhone\LaravelAffiliateSystem\Models\Referral;
use Grhone\LaravelAffiliateSystem\Models\Payment; 
use Auth;
use Exception;

class AffiliateService
{
    /**
     * Register a new affiliate.
     *
     * @param array $data
     * @return Affiliate
     */
    public function registerAffiliate(array $data)
    {

        $userId = Auth::user()->id;

        // Check if the user already has an affiliate account
        $existingAffiliate = Affiliate::where('user_id', $userId)->first();
        if ($existingAffiliate) {
            // Handle the case where the user already has an affiliate account
            throw new Exception('User already has an affiliate account.');
        }

        // If no existing affiliate account, proceed to register a new affiliate
        $data['user_id'] = $userId;

        $affiliate = new Affiliate();
        $affiliate->fill($data);
        $affiliate->save();

        // Dispatch an event after affiliate registration
        AffiliateRegistered::dispatch($affiliate);

        return $affiliate;
    }

}
