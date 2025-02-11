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

    /**
     * Create a new message instance.
     * 
     * This method is used to assign the affiliate to an instance variable for further use.
     * @param Affiliate $affiliate - An instance of the Affiliate class representing the newly registered affiliate.
     * @return void
     */
    public function __construct(Affiliate $affiliate)
    {
        $this->affiliate = $affiliate;
    }

    /**
     * Build the message.
     * 
     * This method builds the email to be sent, including setting the view (markdown file), the subject line and 
     * any data that should be passed to the markdown file.
     * @return $this - Returns a new instance of the Mailable class with the built email content.
     */
    public function build()
    {
        return $this->markdown('laravel-affiliate-system::emails.affiliate_registration')
                    ->subject( config('app.name') . ' - Affiliate Account Registration')
                    ->with([
                        'affiliate' => $this->affiliate,
                    ]);
    }
}
