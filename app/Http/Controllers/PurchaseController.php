<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionTier;
use Farayaz\Larapay\Exceptions\LarapayException;
use Farayaz\Larapay\Larapay;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{

    protected $larapay;

    public function __construct(Larapay $larapay)
    {
        $this->larapay = $larapay;
    }

    public function index(SubscriptionTier  $subscriptionTier)
    {
        return view('dash.purchase.receipt', compact('subscriptionTier'));
    }

    public function paymentProcess(Request $request)
    {
        // Get configuration from config file
        $gatewayConfig = [
            'merchant_id' => config('larapay.gateways.test.merchant_id'),
        ];

        // Payment details
        $paymentData = [
            'amount' => 10000,
            'id' => 1, // Helper method to generate unique ID
            'callbackUrl' => route('purchase.verify'),
            'nationalId' => '1234567890',
            'mobile' => '09131234567',
        ];

        try {
            // Initialize gateway
            $gateway = $this->larapay->gateway('test', $gatewayConfig);

            // Request payment
            $result = $gateway->request(
                id: $paymentData['id'],
                amount: $paymentData['amount'],
                callbackUrl: $paymentData['callbackUrl'],
                nationalId: $paymentData['nationalId'],
                mobile: $paymentData['mobile'],
            );

            // Redirect to payment page
            return $gateway->redirect(
                id: $paymentData['id'],
                token: "dasf",
                callbackUrl: $paymentData['callbackUrl']
            );

        } catch (LarapayException $e) {
            Log::error('Payment Processing Error', [
                'error' => $e->getMessage(),
                'payment_data' => $paymentData
            ]);

            return $this->handlePaymentError($e);
        }
    }

    public function verify(Request $request)
    {
        dd($request->all());
    }
}
