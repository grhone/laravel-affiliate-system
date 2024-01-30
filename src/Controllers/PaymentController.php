<?php

namespace Grhone\LaravelAffiliateSystem\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Grhone\LaravelAffiliateSystem\Models\Affiliate;
use Grhone\LaravelAffiliateSystem\Models\Payment;
use Grhone\LaravelAffiliateSystem\Services\PaymentService;
use Exception;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        $payments = Payment::all();
        return view('laravel-affiliate-system::payments.index', compact('payments'));
    }

    public function create()
    {
        $affiliates = Affiliate::approved()->get();
        return view('laravel-affiliate-system::payments.create', compact('affiliates'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'affiliate_id' => 'required|exists:affiliates,id',
            'amount' => 'required|numeric',
        ]);

        try {
            // Assuming PaymentService::createPayment handles payment creation including PayPal interaction
            $payment = $this->paymentService->createPayment($request->all());

            return redirect()->route('payments.index')
                             ->with('success', 'Payment successfully processed.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Failed to process payment.');
        }
    }

    public function show($id)
    {
        $payment = Payment::findOrFail($id);
        return view('laravel-affiliate-system::payments.show', compact('payment'));
    }

    public function processAllPayments()
    {
        try {
            $this->paymentService->processAllPayments();

            return redirect()->route('payments.index')
                             ->with('success', 'All payments processed successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Failed to process all payments.');
        }
    }
}
