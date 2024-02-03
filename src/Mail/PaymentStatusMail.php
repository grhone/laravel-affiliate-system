<?php 

namespace Grhone\LaravelAffiliateSystem\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $amount;
    public $affiliate;
    public $status;

    public function __construct($payment)
    {
        $this->affiliate = $payment->affiliate;
        $this->amount = $payment->amount;
        $this->status = $payment->payout_status;
    }

    public function build()
    {
        return $this->markdown('laravel-affiliate-system::emails.payment_status')
                    ->subject( config('app.name') . ' - Affiliate Payment Status - '.ucfirst($this->status) )
                    ->with([
                        'amount' => $this->amount,
                        'affiliate' => $this->affiliate,
                        'status' => $this->status
                    ]);
    }
}
