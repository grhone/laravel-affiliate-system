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

    /**
     * Constructor method
     * Injects the PaymentService instance into the controller
     * @param PaymentService $paymentService 
     */
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Retrieve all payments from the database.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $payments = Payment::all();
        return view('laravel-affiliate-system::payments.index', compact('payments'));
    }

    /**
     * Create a new payment instance.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $affiliates = Affiliate::approved()->get();
        return view('laravel-affiliate-system::payments.create', compact('affiliates'));
    }

    /**
     * Store a newly created payment instance in storage.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Display the specified payment instance.
     * @param int $id - ID of the Payment record in the database.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $payment = Payment::findOrFail($id);
        return view('laravel-affiliate-system::payments.show', compact('payment'));
    }

    /**
     * Process all payments stored in the database.
     * @return \Illuminate\Http\RedirectResponse
     */
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
