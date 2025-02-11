<?php 

namespace Grhone\LaravelAffiliateSystem\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Grhone\LaravelAffiliateSystem\Models\Payment;

class PaymentStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $amount;
    public $affiliate;
    public $status;

    /**
     * Create a new message instance.
     * @param  Payment  $payment
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->affiliate = $payment->affiliate;
        $this->amount = $payment->amount;
        $this->status = $payment->payout_status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
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
