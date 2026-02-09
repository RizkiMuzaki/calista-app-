<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PaymentPlanController extends Controller
{
    /**
     * Menampilkan halaman payment untuk plan
     */
    public function showPaymentPage($plan_id)
    {
        try {
            \Log::info('=== SHOW PAYMENT PAGE START ===');
            \Log::info('User:', ['id' => Auth::id(), 'name' => Auth::user()->name]);

            // Ambil plan dari database menggunakan plan_id dari URL
            $plan = Plan::findOrFail($plan_id);
            
            \Log::info('Plan data:', [
                'plan_id' => $plan->id,
                'plan_name' => $plan->nama_paket,
                'harga_jual' => $plan->harga_jual
            ]);

            // Get payment channels
            $paymentChannelsResponse = $this->getPaymentChannels();
            $paymentChannels = [];

            if ($paymentChannelsResponse->getData()->success) {
                $channels = $paymentChannelsResponse->getData()->data;
                // Convert each channel to object if it's an array and normalize numeric fields
                $paymentChannels = array_map(function ($channel) {
                    $obj = is_array($channel) ? (object)$channel : $channel;
                    // Normalize numeric fields to int
                    foreach (['fee_customer', 'fee_merchant', 'total_fee', 'minimum_fee', 'maximum_fee'] as $field) {
                        if (isset($obj->$field)) {
                            // If it's an object, try to get its value as int
                            if (is_object($obj->$field)) {
                                $obj->$field = (int) (property_exists($obj->$field, 'value') ? $obj->$field->value : 0);
                            } else {
                                $obj->$field = (int) $obj->$field;
                            }
                        } else {
                            $obj->$field = 0;
                        }
                    }
                    return $obj;
                }, $channels);
            }

            // Prepare payment data
            $paymentData = [
                'type' => 'subscription',
                'plan' => $plan,
                'item_name' => 'Paket ' . $plan->nama_paket . ' - ' . $plan->durasi_bulan . ' Bulan',
                'amount' => $plan->harga_jual
            ];

            \Log::info('Rendering payment page with data:', $paymentData);
            \Log::info('=== SHOW PAYMENT PAGE END ===');

            return view('plan.payment.page', compact('paymentData', 'paymentChannels'));
        } catch (\Exception $e) {
            \Log::error('Error showing plan payment page: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('premium.show')
                ->with('error', 'Terjadi kesalahan saat memuat halaman pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Get payment channels
     */
    public function getPaymentChannels()
    {
        try {
            $apiKey = config('tripay.api_key');
            $mode = config('tripay.mode', 'sandbox');

            $baseUrl = $mode === 'production'
                ? 'https://tripay.co.id/api/'
                : 'https://tripay.co.id/api-sandbox/';

            $client = new \GuzzleHttp\Client([
                'force_ip_resolve' => 'v4'
            ]);
            $response = $client->get($baseUrl . 'merchant/payment-channel', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                ],
                'timeout' => 30
            ]);

            $data = json_decode($response->getBody(), true);

            // Filter hanya channel yang aktif
            $activeChannels = [];
            if (isset($data['data']) && is_array($data['data'])) {
                $activeChannels = array_filter($data['data'], function ($channel) {
                    $active = $channel['active'] ?? false;
                    if (is_string($active)) {
                        $active = strtolower($active) === 'true';
                    }
                    return $active;
                });
            }

            return response()->json([
                'success' => true,
                'data' => array_values($activeChannels)
            ]);
        } catch (\Exception $e) {
            \Log::error('Get Payment Channels Error: ' . $e->getMessage());
            return $this->getStaticPaymentChannels();
        }
    }

    private function getStaticPaymentChannels()
    {
        $staticChannels = [
            [
                'group' => 'E-Wallet',
                'code' => 'QRISC',
                'name' => 'QRIS',
                'type' => 'direct',
                'fee_merchant' => 0,
                'fee_customer' => 0,
                'total_fee' => 0,
                'minimum_fee' => 0,
                'maximum_fee' => 0,
                'icon_url' => 'https://tripay.co.id/images/payment-channel/QRISC.png',
                'active' => true
            ],
            [
                'group' => 'Virtual Account',
                'code' => 'BCAVA',
                'name' => 'BCA Virtual Account',
                'type' => 'direct',
                'fee_merchant' => 0,
                'fee_customer' => 4000,
                'total_fee' => 4000,
                'minimum_fee' => 0,
                'maximum_fee' => 0,
                'icon_url' => 'https://tripay.co.id/images/payment-channel/BCAVA.png',
                'active' => true
            ],
            [
                'group' => 'Virtual Account',
                'code' => 'BRIVA',
                'name' => 'BRI Virtual Account',
                'type' => 'direct',
                'fee_merchant' => 0,
                'fee_customer' => 4000,
                'total_fee' => 4000,
                'icon_url' => 'https://tripay.co.id/images/payment-channel/BRIVA.png',
                'active' => true
            ],
            [
                'group' => 'Virtual Account',
                'code' => 'BNIVA',
                'name' => 'BNI Virtual Account',
                'type' => 'direct',
                'fee_merchant' => 0,
                'fee_customer' => 4000,
                'total_fee' => 4000,
                'icon_url' => 'https://tripay.co.id/images/payment-channel/BNIVA.png',
                'active' => true
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $staticChannels,
            'note' => 'Using static fallback data for plan'
        ]);
    }

    /**
     * Handle the creation of a plan payment and redirect to Tripay checkout.
     */
    public function createPlanPayment(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'payment_method' => 'required|string'
        ]);

        try {
            $user = $request->user();
            
            // 🎯 PERBAIKAN: Cek apakah user sudah punya subscription aktif
            if ($user->hasActiveSubscription()) {
                return back()->with('error', 'Anda sudah memiliki subscription aktif. Tidak perlu membeli paket lagi.');
            }

            $plan = Plan::findOrFail($request->plan_id);

            // Generate merchant_ref
            $merchantRef = 'PLAN-' . time() . '-' . $user->id . '-' . $plan->id;

            // Prepare order items
            $orderItems = [[
                'sku' => 'PLAN-' . $plan->id,
                'name' => $plan->nama_paket . ' - ' . $plan->durasi_bulan . ' Bulan',
                'price' => (int) $plan->harga_jual,
                'quantity' => 1,
                'product_url' => url('/premium'),
                'image_url' => ''
            ]];

            $amount = (int) $plan->harga_jual;

            // Signature Tripay
            $merchantCode = config('tripay.merchant_code');
            $privateKey = config('tripay.private_key');
            $signatureString = $merchantCode . $merchantRef . $amount;
            $signature = hash_hmac('sha256', $signatureString, $privateKey);

            $transactionData = [
                'method'            => $request->payment_method,
                'merchant_ref'      => $merchantRef,
                'amount'            => $amount,
                'customer_name'     => trim($user->name),
                'customer_email'    => trim($user->email),
                'customer_phone'    => $user->no_telp ?? '081234567890',
                'order_items'       => $orderItems,
                'return_url'        => route('plan.payment.status', ['reference' => $merchantRef]),
                'expired_time'      => (int) (time() + (24 * 60 * 60)),
                'signature'         => $signature
            ];

            $apiKey = config('tripay.api_key');
            $mode = config('tripay.mode', 'sandbox');
            $baseUrl = $mode === 'production'
                ? 'https://tripay.co.id/api/'
                : 'https://tripay.co.id/api-sandbox/';

            $client = new \GuzzleHttp\Client([
                'force_ip_resolve' => 'v4'
            ]);
            $response = $client->post($baseUrl . 'transaction/create', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $transactionData,
                'timeout' => 30
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['success']) {
                // Create payment record
                $payment = Payment::create([
                    'pesanan_id' => null,
                    'plan_id' => $plan->id,
                    'user_id' => $user->id,
                    'reference' => $data['data']['reference'],
                    'merchant_ref' => $merchantRef,
                    'payment_method' => $data['data']['payment_method'],
                    'payment_name' => $data['data']['payment_name'],
                    'amount' => $data['data']['amount'],
                    'fee_merchant' => $data['data']['fee_merchant'],
                    'fee_customer' => $data['data']['fee_customer'],
                    'total_fee' => $data['data']['total_fee'],
                    'amount_received' => $data['data']['amount_received'],
                    'pay_code' => $data['data']['pay_code'] ?? null,
                    'pay_url' => $data['data']['pay_url'] ?? null,
                    'checkout_url' => $data['data']['checkout_url'],
                    'status' => $data['data']['status'],
                    'expired_time' => $data['data']['expired_time'],
                ]);

                // Redirect user to Tripay checkout page (external)
                // Tripay will redirect back to route('plan.payment.status', ['reference' => $merchantRef]) after payment
                return redirect()->away($data['data']['checkout_url']);
            } else {
                throw new \Exception($data['message'] ?? 'Unknown error from Tripay');
            }
        } catch (\Exception $e) {
            \Log::error('Plan Tripay Payment Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Callback/return handler for plan payments.
     * This will check Tripay transaction detail for the given reference,
     * update the Payment record, and trigger GA4 tracking.
     * Subscription creation is handled in PaymentController::processPaymentCallback
     */
    public function paymentStatus(Request $request, $reference = null)
    {
        try {
            $reference = $reference ?? $request->query('reference') ?? $request->query('merchant_ref') ?? null;
            if (!$reference) {
                throw new \Exception('No reference provided');
            }

            \Log::info('🎯 PLAN PAYMENT STATUS CHECK', [
                'reference' => $reference
            ]);

            // Find payment by reference or merchant_ref
            $payment = Payment::where('reference', $reference)
                ->orWhere('merchant_ref', $reference)
                ->firstOrFail();

            // Query Tripay untuk latest transaction detail
            $apiKey = config('tripay.api_key');
            $mode = config('tripay.mode', 'sandbox');
            $baseUrl = $mode === 'production'
                ? 'https://tripay.co.id/api/'
                : 'https://tripay.co.id/api-sandbox/';

            $client = new \GuzzleHttp\Client(['force_ip_resolve' => 'v4']);
            $response = $client->get($baseUrl . 'transaction/detail?reference=' . $reference, [
                'headers' => ['Authorization' => 'Bearer ' . $apiKey],
                'timeout' => 15
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (!isset($data['success']) || !$data['success']) {
                throw new \Exception($data['message'] ?? 'Failed to get transaction detail from Tripay');
            }

            $tripayData = $data['data'];
            $newStatus = strtoupper($tripayData['status'] ?? '');

            \Log::info('📊 TRIPAY RESPONSE FOR PLAN PAYMENT', [
                'reference' => $reference,
                'status' => $newStatus,
                'amount' => $tripayData['amount'] ?? null,
                'paid_at' => $tripayData['paid_at'] ?? null
            ]);

            $updateData = ['status' => $newStatus];
            if (!empty($tripayData['paid_at'])) {
                $updateData['paid_at'] = date('Y-m-d H:i:s', $tripayData['paid_at']);
            }
            if (isset($tripayData['amount_received'])) {
                $updateData['amount_received'] = $tripayData['amount_received'];
            }

            $payment->update($updateData);
            $payment->refresh();

            \Log::info('✅ PLAN PAYMENT STATUS UPDATED', [
                'payment_id' => $payment->id,
                'reference' => $payment->reference,
                'status' => $payment->status
            ]);

            // Note: Subscription creation is now handled in PaymentController::processPaymentCallback
            // which is triggered on successful callback from Tripay

            // Return a view for the user
            return view('payment.status', ['payment' => $payment, 'order' => null]);

        } catch (\Exception $e) {
            \Log::error('Plan payment status check error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'line' => $e->getLine()
            ]);
            return redirect()->route('premium.show')->with('error', 'Gagal memeriksa status pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Track purchase to GA4 using Measurement Protocol.
     * Accepts plan payments as well as order payments.
     */
 
    
    
}