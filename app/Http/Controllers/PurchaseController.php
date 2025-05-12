<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionTier;
use Farayaz\Larapay\Exceptions\LarapayException;
use Farayaz\Larapay\Larapay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Devrabiul\ToastMagic\Facades\ToastMagic;

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

        // Generate a unique payment ID
        $paymentId = time() . rand(1000, 9999);

        // Payment details
        $paymentData = [
            'amount' => 10000,
            'id' => $paymentId,
            'callbackUrl' => route('purchase.verify'),
            'nationalId' => '1234567890',
            'mobile' => '09131234567',
        ];

        // Store payment data in session
        session([
            'payment_id' => $paymentId,
            'payment_amount' => 10000,
            'payment_national_id' => $paymentData['nationalId'],
            'payment_mobile' => $paymentData['mobile']
        ]);

        try {
            $gateway = $this->larapay->gateway('test', $gatewayConfig);

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
        try {
            // Get configuration from config file
            $gatewayConfig = [
                'merchant_id' => config('larapay.gateways.test.merchant_id'),
            ];

            // Get payment data from session
            $id = session('payment_id');
            $amount = session('payment_amount');
            $nationalId = session('payment_national_id');
            $mobile = session('payment_mobile');

            if (empty($id)) {
                throw new LarapayException('Payment ID is required');
            }

            if (empty($amount)) {
                throw new LarapayException('Payment amount is required');
            }

            if (empty($nationalId)) {
                throw new LarapayException('National ID is required');
            }

            if (empty($mobile)) {
                throw new LarapayException('Mobile number is required');
            }

            // Get all request parameters
            $params = $request->all();

            // Log payment verification attempt
            Log::info('Payment verification attempt', [
                'session_id' => $id,
                'session_amount' => $amount,
                'request_params' => $params
            ]);

            // Verify the payment
            $result = $this->larapay->gateway('test', $gatewayConfig)
                ->verify(
                    (int) $id,
                    (int) $amount,
                    $nationalId,
                    $mobile,
                    $request->input('token', ''),
                    $params
                );

            // Payment verified successfully
            // Create transaction record
            $transaction = [
                'result' => $result['result'],
                'reference_id' => $result['reference_id'],
                'tracking_code' => $result['tracking_code'],
                'card' => $result['card'],
                'fee' => $result['fee']
            ];

            // TODO: Save transaction record to database

            ToastMagic::success('پرداخت با موفقیت انجام شد. کد پیگیری: ' . $result['tracking_code']);
            return redirect()->route('dashboard');

        } catch (LarapayException $e) {
            return $this->handlePaymentError($e);
        }
    }

    private function handlePaymentError(LarapayException|\Exception $e)
    {
        // Log the error
        Log::error('Payment Error', [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'trace' => $e->getTraceAsString()
        ]);

        ToastMagic::error('خطا در تایید پرداخت: ' . $e->getMessage());
        return redirect()->route('dashboard');
    }
}
