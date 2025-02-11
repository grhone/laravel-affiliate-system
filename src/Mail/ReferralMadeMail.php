<?php

namespace Grhone\LaravelAffiliateSystem\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Grhone\LaravelAffiliateSystem\Models\Referral;

class ReferralMadeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $referral;

    /**
     * Create a new message instance.
     * 
     * @param Referral $referral
     * @return void
     */
    public function __construct(Referral $referral)
    {
        $this->referral = $referral;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('laravel-affiliate-system::emails.referral_made')
                    ->subject( config('app.name') . ' - Referral Made')
                    ->with([
                        'affiliate' => $this->referral->affiliate,
                        'referral' => $this->referral,
                    ]);
    }
}
