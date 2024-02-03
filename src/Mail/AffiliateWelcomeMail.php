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

    public function __construct(Affiliate $affiliate)
    {
        $this->affiliate = $affiliate;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('affiliate.admin_email'), config('app.name')),
            subject: 'Welcome to Our Affiliate Program!',
        );
    }

    /**
     * Get the message content definition.
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
