<?php

namespace Grhone\LaravelAffiliateSystem\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Grhone\LaravelAffiliateSystem\Models\Affiliate;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;

class AffiliateWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $affiliate;

    /**
     * Constructor for the AffiliateWelcomeMail class.
     * 
     * @param Affiliate $affiliate - The affiliate instance to send welcome email to.
     */
    public function __construct(Affiliate $affiliate)
    {
        $this->affiliate = $affiliate;
    }

    /**
     * Get the message envelope.
     * 
     * @return Envelope - The envelope containing the sender address and subject line.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Welcome to Our Affiliate Program!',
        );
    }

    /**
     * Get the message content definition.
     * 
     * @return Content - The content of the email, including markdown file path and affiliate data.
     */
    public function content(): Content
    {
        return new Content(
                    markdown:   'laravel-affiliate-system::emails.affiliate_welcome',
                    with:
                    [
                        'affiliate' => $this->affiliate,
                    ]
                );

    }
}
