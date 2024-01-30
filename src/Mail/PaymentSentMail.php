<?php 

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentSentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $amount;
    public $user;

    public function __construct($user, $amount)
    {
        $this->user = $user;
        $this->amount = $amount;
    }

    public function build()
    {
        return $this->view('laravel-affiliate-system::emails.payment_sent')
                    ->subject('Payment Received')
                    ->with([
                        'amount' => $this->amount,
                        'user' => $this->user,
                    ]);
    }
}
