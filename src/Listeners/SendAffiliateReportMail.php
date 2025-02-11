<?php

namespace Grhone\LaravelAffiliateSystem\Listeners;

use Grhone\LaravelAffiliateSystem\Events\AffiliateReportEvent;
use Grhone\LaravelAffiliateSystem\Mail\AffiliateReportMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendAffiliateReportMail implements ShouldQueue
{
    
    /**
     * Handle the event.
     * 
     * Checks if the affiliate user has enabled the 'notification_monthly_report' setting in their settings, and sends them an email with their report data if they have.
     *
     * @param AffiliateReportEvent $event The event being handled.
     * @return void
     */
    public function handle(AffiliateReportEvent $event)
    {
        // Check if user wants monthly reports (from settings)
        $wantsReport = $event->affiliate->settings
            ->where('key', 'notification_monthly_report')
            ->first()?->value;

        if($wantsReport) {

                // Calculate sales and earnings for the last month
            Mail::to($event->affiliate->user->email)
                ->send(new AffiliateReportMail(
                    $event->affiliate,
                    $event->reportData['sales'],
                    $event->reportData['earnings']
                ));
        }
    }
} 