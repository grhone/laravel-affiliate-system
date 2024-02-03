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

    public function __construct(ReferredTransaction $referredTransaction)
    {
        $this->referredTransaction = $referredTransaction;
    }

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
