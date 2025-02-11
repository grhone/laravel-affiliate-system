<?php

namespace Grhone\LaravelAffiliateSystem\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Grhone\LaravelAffiliateSystem\Models\Affiliate;

class AffiliateReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $affiliate;
    public $monthlySales;
    public $monthlyEarnings;

    /**
     * Create a new message instance.
     * 
     * @param Affiliate $affiliate
     * @param float $monthlySales
     * @param float $monthlyEarnings
     * 
     * @return void
     */
    public function __construct(Affiliate $affiliate, $monthlySales, $monthlyEarnings)
    {
        $this->affiliate = $affiliate;
        $this->monthlySales = $monthlySales;
        $this->monthlyEarnings = $monthlyEarnings;
    }

    /**
     * Build the message.
     * 
     * @return $this
     */
    public function build()
    {
        return $this->markdown('laravel-affiliate-system::emails.affiliate_report')
            ->subject(config('app.name') . ' - Monthly Affiliate Report')
            ->with([
                'affiliate' => $this->affiliate,
                'monthlySales' => $this->monthlySales,
                'monthlyEarnings' => $this->monthlyEarnings
            ]);
    }
} 