<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\SubscriptionTier;
use App\Models\SubscriptionUser;
use Carbon\Carbon;
use Farayaz\Larapay\Exceptions\LarapayException;
use Farayaz\Larapay\Larapay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        if (auth()->user()->hasActiveSubscriptionTier()) {
            ToastMagic::error('شما اشتراک فعال دارید');
            return redirect()->route('dashboard');
        }
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

        // Get subscription tier
        $subscriptionTier = SubscriptionTier::findOrFail($request->subscription_tier_id);

        // Payment details
        $paymentData = [
            'amount' => $subscriptionTier->price,
            'id' => $paymentId,
            'callbackUrl' => route('purchase.verify'),
            'nationalId' => '1234567890', // TODO: Get from user profile
            'mobile' => '09131234567',    // TODO: Get from user profile
        ];

        try {
            $gateway = $this->larapay->gateway('test', $gatewayConfig);

            $result = $gateway->request(
                id: $paymentData['id'],
                amount: $paymentData['amount'],
                callbackUrl: $paymentData['callbackUrl'],
                nationalId: $paymentData['nationalId'],
                mobile: $paymentData['mobile'],
            );

            $receipt = Receipt::create([
                'receipt_number' => $paymentId,
                'user_id' => Auth::id(),
                'subscription_user_id' => null,
                'amount' => $subscriptionTier->price,
                'status' => 'pending',
                'currency' => 'IRR',
                'payment_method' => 'online',
                'meta_data' => [
                    'subscription_tier_id' => $subscriptionTier->id,
                    'national_id' => $paymentData['nationalId'],
                    'mobile' => $paymentData['mobile'],
                    'payment_id' => $paymentId
                ],
                'paid_at' => null
            ]);

            // Store minimal data in session for verification
            session([
                'payment_id' => $paymentId
            ]);

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

            // Get payment ID from session
            $id = session('payment_id');

            if (empty($id)) {
                throw new LarapayException('Payment ID is required');
            }

            // Find the receipt with waiting_to_pay status
            $receipt = Receipt::where('receipt_number', $id)
                ->where('status', 'pending')
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Extract payment data from receipt
            $amount = $receipt->amount;
            $metaData = $receipt->meta_data;
            $nationalId = $metaData['national_id'] ?? '';
            $mobile = $metaData['mobile'] ?? '';
            $subscriptionTierId = $metaData['subscription_tier_id'] ?? null;

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

            // Start database transaction
            DB::beginTransaction();

            try {
                // Create subscription
                $subscription = SubscriptionUser::create([
                    'user_id' => Auth::id(),
                    'subscription_tier_id' => $subscriptionTierId,
                    'status' => 'active',
                    'starts_at' => now(),
                    'ends_at' => now()->addMonth() // TODO: Get duration from subscription tier
                ]);

                // Update the existing receipt
                $receipt->update([
                    'subscription_user_id' => $subscription->id,
                    'status' => 'paid',
                    'meta_data' => array_merge($metaData, [
                        'result' => $result['result'],
                        'reference_id' => $result['reference_id'],
                        'tracking_code' => $result['tracking_code'],
                        'card' => $result['card'],
                        'fee' => $result['fee']
                    ]),
                    'paid_at' => now()
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }


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

    public function subscriptionIndex()
    {
        $subscriptionTiers = SubscriptionTier::all();
        return view('dash.subscription.all', compact('subscriptionTiers'));
    }
}
