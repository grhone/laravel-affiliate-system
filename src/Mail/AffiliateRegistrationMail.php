<?php

namespace Grhone\LaravelAffiliateSystem\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Grhone\LaravelAffiliateSystem\Models\Affiliate;

class AffiliateRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $affiliate;

    public function __construct(Affiliate $affiliate)
    {
        $this->affiliate = $affiliate;
    }

    public function build()
    {
        return $this->markdown('laravel-affiliate-system::emails.affiliate_registration')
                    ->subject( config('app.name') . ' - Affiliate Account Registration')
                    ->with([
                        'affiliate' => $this->affiliate,
                    ]);
    }
}
