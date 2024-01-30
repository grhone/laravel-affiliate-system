<?php

namespace Grhone\LaravelAffiliateSystem\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Grhone\LaravelAffiliateSystem\Services\PaymentService;

class ProcessAffiliatePayments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Constructor contents, if needed
    }

    /**
     * Execute the job.
     *
     * @param PaymentService $paymentService
     * @return void
     */
    public function handle(PaymentService $paymentService)
    {
        // Process payments for all affiliates
        $paymentService->processAllPayments();
    }
}
