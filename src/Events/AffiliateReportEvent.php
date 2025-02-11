<?php

namespace Grhone\LaravelAffiliateSystem\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Grhone\LaravelAffiliateSystem\Models\Affiliate;

class AffiliateReportEvent
{
    use Dispatchable, SerializesModels;

    public $affiliate;
    public $reportData;

    /**
     * Create a new event instance.
     *
     * @param Affiliate $affiliate
     * @param array $reportData
     * @return void
     */
    public function __construct(Affiliate $affiliate, array $reportData)
    {
        $this->affiliate = $affiliate;
        $this->reportData = $reportData;
    }
} 