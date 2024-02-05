<?php

namespace Grhone\LaravelAffiliateSystem\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Grhone\LaravelAffiliateSystem\Services\PaymentService;

class PaypalWebhookController extends Controller
{
    public function handle(Request $request, PaymentService $paymentService)
    {
        $eventType = $request->input('event_type');
        $eventData = $request->input('resource');

        switch ($eventType) {
            case 'PAYMENT.PAYOUTS_ITEM.SUCCEEDED':
                $paymentService->updatePaymentStatus($eventData, 'succeeded');
                break;
            case 'PAYMENT.PAYOUTS_ITEM.FAILED':
                $paymentService->updatePaymentStatus($eventData, 'failed');
                break;
            // Handle other event types as needed
        }

        return response()->json(['status' => 'success'], 200);
    }
}
