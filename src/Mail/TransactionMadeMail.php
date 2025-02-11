<?php

namespace Grhone\LaravelAffiliateSystem\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Grhone\LaravelAffiliateSystem\Models\ReferredTransaction;

class TransactionMadeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $referredTransaction;

    /**
     * Create a new message instance.
     * 
     * @param ReferredTransaction $referredTransaction
     * @return void
     */ 
    public function __construct(ReferredTransaction $referredTransaction)
    {
        $this->referredTransaction = $referredTransaction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('laravel-affiliate-system::emails.transaction_made')
                    ->subject( config('app.name') . ' - Transaction Made')
                    ->with([
                        'affiliate' => $this->referredTransaction->referral->affiliate,
                        'transaction' => $this->referredTransaction,
                    ]);
    }
}
