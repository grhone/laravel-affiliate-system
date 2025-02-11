<?php

namespace Grhone\LaravelAffiliateSystem\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Grhone\LaravelAffiliateSystem\Services\PaymentService;

class PaypalWebhookController extends Controller
{
    
    /**
     * Handle a webhook call from PayPal.
     * This method is used to update the status of a payment based on the event type received.
     * 
     * @param Request $request The incoming request object.
     * @param PaymentService $paymentService The service that handles payment related tasks.
     * 
     * @return \Illuminate\Http\JsonResponse Returns a json response with status 'success'.
     */
    public function handle(Request $request, PaymentService $paymentService)
    {
        // Get the event type and data from the request object.
        $eventType = $request->input('event_type');
        $eventData = $request->input('resource');

        // Handle different event types based on their type.
        switch ($eventType) {
            case 'PAYMENT.PAYOUTS_ITEM.SUCCEEDED':
                // If the event type is a successful payment, update the status of the payment to 'succeeded'.
                $paymentService->updatePaymentStatus($eventData, 'succeeded');
                break;
            case 'PAYMENT.PAYOUTS_ITEM.FAILED':
                // If the event type is a failed payment, update the status of the payment to 'failed'.
                $paymentService->updatePaymentStatus($eventData, 'failed');
                break;
            // Handle other event types as needed
        }

        return response()->json(['status' => 'success'], 200);
    }
}
