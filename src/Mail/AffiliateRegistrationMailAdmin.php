<?php

namespace Grhone\LaravelAffiliateSystem\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Grhone\LaravelAffiliateSystem\Models\Affiliate;

class AffiliateRegistrationMailAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $affiliate;

    /** 
     * Constructor function for the class.
     *
     * @param Affiliate $affiliate An instance of the Affiliate model representing the newly registered affiliate.
     */
    public function __construct(Affiliate $affiliate)
    {
        $this->affiliate = $affiliate;
    }

    /**
     * Build the message.
     *
     * This function sets up and returns a Mailable object with markdown content, subject line, 
     * and an array of data to be passed to the markdown view. The markdown file used is 'laravel-affiliate-system::emails.affiliate_registration_admin',
     * the email's subject is set to include the name of the app with a predefined message, and the affiliate instance 
     * is passed as data for the markdown view.
     *
     * @return $this Returns the Mailable object ready to be sent.
     */
    public function build()
    {
        return $this->markdown('laravel-affiliate-system::emails.affiliate_registration_admin')
                    ->subject( config('app.name') . ' - New Affiliate Account Registration')
                    ->with([
                        'affiliate' => $this->affiliate,
                    ]);
    }
}
