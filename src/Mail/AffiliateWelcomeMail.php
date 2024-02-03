<?php

namespace Grhone\LaravelAffiliateSystem\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Grhone\LaravelAffiliateSystem\Models\Affiliate;

class AffiliateWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $affiliate;

    public function __construct(Affiliate $affiliate)
    {
        $this->affiliate = $affiliate;
    }

    public function build()
    {
        return $this->markdown('laravel-affiliate-system::emails.affiliate_welcome')
                    ->subject( config('app.name') . ' - Welcome to Our Affiliate Program!')
                    ->with([
                        'affiliate' => $this->affiliate,
                    ]);
    }
}
