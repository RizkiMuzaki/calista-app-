<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:pesanans,id',
            'payment_method' => 'required|string'
        ]);

        try {
            // Load order dengan relationships
            $order = Pesanan::with([
                'user', 
                'details.variasiProduk.produk',
                'details.variasiProduk.stokProduks'
            ])->findOrFail($request->order_id);

            // Generate merchant reference
            $merchantRef = 'INV-' . time() . '-' . $order->id;
            
            // Prepare order items dan HITUNG TOTAL DARI ORDER ITEMS
            $orderItems = [];
            $calculatedTotalProduk = 0;

            foreach ($order->details as $detail) {
                $variasiProduk = $detail->variasiProduk;
                $produk = $variasiProduk->produk ?? null;
                
                if ($produk && $variasiProduk) {
                    $latestStok = $variasiProduk->stokProduks->sortByDesc('created_at')->first();
                    $hargaJual = $latestStok->harga_jual ?? $detail->harga_satuan ?? 0;
                    $itemTotal = (int) $hargaJual * (int) $detail->jumlah;
                    $calculatedTotalProduk += $itemTotal;
                    
                    $orderItems[] = [
                        'sku' => $produk->kode_produk ?? 'PROD-' . $produk->id,
                        'name' => $produk->nama_produk ?? 'Product',
                        'price' => (int) $hargaJual,
                        'quantity' => (int) $detail->jumlah,
                        'product_url' => url('/produk/' . $produk->id),
                        'image_url' => $variasiProduk->gambar_url ?? ($produk->gambar ? url('storage/' . $produk->gambar) : '')
                    ];
                }
            }

            // Cek diskon member
            $user = $order->user;
            $hasActiveSubscription = false;
            $discountAmount = 0;
            if ($user) {
                $activeSub = Subscription::where('user_id', $user->id)
                    ->where('status', 'aktif')
                    ->where('tanggal_berakhir', '>', now())
                    ->first();
                $hasActiveSubscription = !!$activeSub;
                if ($hasActiveSubscription && $calculatedTotalProduk >= 150000) {
                    $discountAmount = 10000;
                }
            }

            // Tambahkan item diskon jika ada
            if ($discountAmount > 0) {
                $orderItems[] = [
                    'sku' => 'DISKON-MEMBER',
                    'name' => 'Diskon Member',
                    'price' => -$discountAmount,
                    'quantity' => 1,
                    'product_url' => url('/'),
                    'image_url' => ''
                ];
            }

            // Ambil ongkir dari pesanan (jika ada) dan tambahkan sebagai item
            $ongkir = (int) ($order->ongkir ?? 0);
            if ($ongkir > 0) {
                $orderItems[] = [
                    'sku' => 'ONGKIR-' . $order->id,
                    'name' => 'Ongkir',
                    'price' => $ongkir,
                    'quantity' => 1,
                    'product_url' => url('/'),
                    'image_url' => ''
                ];
            }

            // Hitung total amount yang akan dibayar
            $amount = $calculatedTotalProduk - $discountAmount + $ongkir;

            // DEBUG: Bandingkan total dari database vs calculated
            Log::info('TOTAL COMPARISON:', [
                'database_total' => $order->total_harga,
                'calculated_total_produk' => $calculatedTotalProduk,
                'discount' => $discountAmount,
                'ongkir' => $ongkir,
                'final_amount' => $amount
            ]);

            if (empty($orderItems)) {
                $orderItems[] = [
                    'sku' => 'ORDER-' . $order->id,
                    'name' => 'Pesanan #' . $order->id,
                    'price' => $amount,
                    'quantity' => 1,
                    'product_url' => url('/'),
                    'image_url' => ''
                ];
            }

            // **PERBAIKAN SIGNATURE SESUAI DOKUMENTASI TRIPAY**
            $merchantCode = config('tripay.merchant_code');
            $privateKey = config('tripay.private_key');
            
            // Format signature sesuai dokumentasi: merchantCode + merchantRef + amount
            $signatureString = $merchantCode . $merchantRef . $amount;
            $signature = hash_hmac('sha256', $signatureString, $privateKey);

            Log::info('Signature Generation Details:', [
                'merchant_code' => $merchantCode,
                'merchant_ref' => $merchantRef,
                'amount' => $amount,
                'calculated_total' => $calculatedTotalProduk,
                'signature_string' => $signatureString,
                'signature' => $signature
            ]);

            // **PERBAIKAN: Gunakan format data yang sesuai dengan Tripay API**
            $transactionData = [
                'method'            => $request->payment_method,
                'merchant_ref'      => $merchantRef,
                'amount'            => $amount,
                'customer_name'     => trim($order->user->name),
                'customer_email'    => trim($order->user->email),
                'customer_phone'    => $order->user->no_telp ?? '081234567890',
                'order_items'       => $orderItems,
                'return_url'        => url('/payment/status'),
                'expired_time'      => (int) (time() + (24 * 60 * 60)), // 24 jam
                'signature'         => $signature
            ];

            Log::info('Transaction Data to Tripay:', $transactionData);

            // **VERIFIKASI: Pastikan total order_items sama dengan amount**
            $itemsTotal = array_reduce($orderItems, function($carry, $item) {
                return $carry + ($item['price'] * $item['quantity']);
            }, 0);

            if ($itemsTotal !== $amount) {
                throw new \Exception("Total inconsistency: order_items total = {$itemsTotal}, amount = {$amount}");
            }

            // **APPROACH: Gunakan HTTP Client langsung**
            $apiKey = config('tripay.api_key');
            $mode = config('tripay.mode', 'sandbox');
            
            $baseUrl = $mode === 'production' 
                ? 'https://tripay.co.id/api/' 
                : 'https://tripay.co.id/api-sandbox/';
            
            $client = new \GuzzleHttp\Client([
                'force_ip_resolve' => 'v4'
            ]);
            
            try {
                Log::info('Sending request to Tripay API...');
                
                $response = $client->post($baseUrl . 'transaction/create', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $transactionData,
                    'timeout' => 30
                ]);
                
                $responseBody = $response->getBody()->getContents();
                $data = json_decode($responseBody, true);
                
                Log::info('Tripay API Direct Response:', $data);
                
                if ($data['success']) {
                    // Save payment record
                    $payment = Payment::create([
                        'pesanan_id' => $order->id,
                        'user_id' => $order->user_id,
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

                    // **LOG DETAILED PAYMENT CREATION**
                    Log::info('🆕 PAYMENT CREATED SUCCESSFULLY', [
                        'payment_id' => $payment->id,
                        'reference' => $payment->reference,
                        'amount' => $payment->amount,
                        'status' => $payment->status,
                        'user_id' => $payment->user_id,
                        'order_id' => $payment->pesanan_id
                    ]);

                    return response()->json([
                        'success' => true,
                        'reference' => $data['data']['reference'],
                        'checkout_url' => $data['data']['checkout_url'],
                        'payment' => $payment
                    ]);
                } else {
                    throw new \Exception($data['message'] ?? 'Unknown error from Tripay');
                }
                
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                $errorResponse = $e->getResponse();
                $errorBody = $errorResponse ? $errorResponse->getBody()->getContents() : $e->getMessage();
                
                Log::error('Tripay API Request Exception:', [
                    'error' => $e->getMessage(),
                    'response' => $errorBody
                ]);
                
                // Coba parse error message
                try {
                    $errorData = json_decode($errorBody, true);
                    $errorMessage = $errorData['message'] ?? $errorBody;
                } catch (\Exception $parseError) {
                    $errorMessage = $errorBody;
                }
                
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat transaksi: ' . $errorMessage
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Tripay Payment Error: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Track purchase to GA4 menggunakan Measurement Protocol - DIPERBAIKI
     */
    private function trackPurchase($payment, $order = null)
    {
        try {
            \Log::info('🎯 ATTEMPTING GA4 TRACKING', [
                'payment_id' => $payment->id,
                'reference' => $payment->reference,
                'amount' => $payment->amount,
                'status' => $payment->status,
                'has_order' => !is_null($order),
                'order_id' => $order->id ?? null,
                'user_id' => $payment->user_id
            ]);

            // ✅ VALIDASI EKSTRA: Pastikan payment benar-benar PAID dan amount valid
            if ($payment->status !== 'PAID') {
                \Log::warning('GA4 tracking skipped - payment not PAID', [
                    'payment_id' => $payment->id,
                    'status' => $payment->status
                ]);
                return;
            }
            
            if ($payment->amount <= 0) {
                \Log::warning('GA4 tracking skipped - invalid amount', [
                    'payment_id' => $payment->id,
                    'amount' => $payment->amount
                ]);
                return;
            }

            $measurementId = config('services.ga4.measurement_id');
            $apiSecret = config('services.ga4.api_secret');
            
            if (!$measurementId || !$apiSecret) {
                \Log::warning('GA4 credentials not configured', [
                    'measurement_id_set' => !empty($measurementId),
                    'api_secret_set' => !empty($apiSecret)
                ]);
                return;
            }

            // **CLIENT ID YANG LEBIH AKURAT**
            $clientId = 'user_' . ($payment->user_id ?? 'guest_' . $payment->id);
            
            // **DATA YANG LEBIH DETAIL UNTUK GA4**
            $eventData = [
                'transaction_id' => $payment->reference,
                'value' => (float) $payment->amount,
                'currency' => 'IDR',
                'tax' => 0,
                'shipping' => (float) ($order->ongkir ?? 0),
                'coupon' => ($payment->amount < $order->total_harga ?? $payment->amount) ? 'DISKON-MEMBER' : '',
                'payment_method' => $payment->payment_method,
                'items' => []
            ];

            // **TAMBAHKAN ITEMS DETAIL JIKA ADA ORDER**
            if ($order && $order->details) {
                foreach ($order->details as $detail) {
                    $variasiProduk = $detail->variasiProduk;
                    $produk = $variasiProduk->produk ?? null;
                    
                    if ($produk) {
                        $eventData['items'][] = [
                            'item_id' => $produk->kode_produk ?? 'PROD-' . $produk->id,
                            'item_name' => $produk->nama_produk ?? 'Product',
                            'item_category' => $produk->kategori->nama ?? 'General',
                            'price' => (float) ($detail->harga_satuan ?? 0),
                            'quantity' => (int) $detail->jumlah
                        ];
                    }
                }
            }

            // **JIKA TIDAK ADA ITEMS, GUNAKAN DEFAULT**
            if (empty($eventData['items'])) {
                $eventData['items'] = [
                    [
                        'item_id' => 'order-' . $payment->id,
                        'item_name' => 'Order #' . $payment->reference,
                        'item_category' => 'General',
                        'price' => (float) $payment->amount,
                        'quantity' => 1,
                    ]
                ];
            }

            $result = $this->sendGA4Event($measurementId, $apiSecret, $clientId, 'purchase', $eventData);
            
            if ($result) {
                \Log::info('✅ GA4 PURCHASE TRACKED SUCCESSFULLY', [
                    'payment_id' => $payment->id,
                    'reference' => $payment->reference,
                    'amount' => $payment->amount,
                    'client_id' => $clientId,
                    'items_count' => count($eventData['items'])
                ]);
            } else {
                \Log::error('❌ GA4 PURCHASE TRACKING FAILED', [
                    'payment_id' => $payment->id,
                    'reference' => $payment->reference
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('💥 GA4 TRACKING ERROR: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'reference' => $payment->reference,
                'stack_trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Send event to GA4 Measurement Protocol - DIPERBAIKI
     */
    private function sendGA4Event($measurementId, $apiSecret, $clientId, $eventName, $params)
    {
        try {
            $url = "https://www.google-analytics.com/mp/collect?measurement_id={$measurementId}&api_secret={$apiSecret}";
            
            $data = [
                'client_id' => $clientId,
                'user_id' => str_replace('user_', '', $clientId), // Tambahkan user_id juga
                'events' => [
                    [
                        'name' => $eventName,
                        'params' => $params
                    ]
                ]
            ];

            \Log::info('📤 SENDING GA4 EVENT', [
                'event' => $eventName,
                'client_id' => $clientId,
                'measurement_id' => $measurementId,
                'api_secret_length' => strlen($apiSecret),
                'params' => $params
            ]);

            $client = new \GuzzleHttp\Client();
            $response = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
                'timeout' => 10,
                'verify' => false // Untuk development, hati-hati di production
            ]);

            \Log::info('✅ GA4 EVENT SENT SUCCESSFULLY', [
                'event' => $eventName,
                'transaction_id' => $params['transaction_id'],
                'status_code' => $response->getStatusCode(),
                'client_id' => $clientId,
                'response_body' => $response->getBody()->getContents()
            ]);

            return true;

        } catch (\Exception $e) {
            \Log::error('💥 GA4 SEND EVENT FAILED: ' . $e->getMessage(), [
                'event' => $eventName,
                'client_id' => $clientId,
                'transaction_id' => $params['transaction_id'] ?? 'unknown',
                'url' => $url ?? 'unknown'
            ]);
            return false;
        }
    }

    public function handleCallback(Request $request)
    {
        // LOG SEMUA DATA UNTUK DEBUG
        \Log::info('🎯 TRIPAY CALLBACK RECEIVED', [
            'headers' => $request->headers->all(),
            'data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip()
        ]);

        try {
            $data = $request->all();
            
            // PROCESS DATA LANGSUNG (SYNCHRONOUS, bukan background)
            if (!empty($data['reference']) || !empty($data['merchant_ref'])) {
                $this->processPaymentCallback($data);
                \Log::info('✅ CALLBACK PROCESSED SUCCESSFULLY');
            } else {
                \Log::warning('⚠️ NO REFERENCE OR MERCHANT_REF IN CALLBACK DATA');
            }
            
            // **RESPONSE YANG DIMINTA TRIPAY - SELALU SUCCESS**
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            \Log::error('💥 CALLBACK ERROR BUT STILL SUCCESS: ' . $e->getMessage());
            // **MESKIPUN ERROR, TETAP RETURN SUCCESS UNTUK TRIPAY**
            return response()->json(['success' => true]);
        }
    }

    private function processPaymentCallback($data)
    {
        try {
            \Log::info('🔄 PROCESSING PAYMENT CALLBACK', $data);
            
            $reference = $data['reference'] ?? '';
            $merchantRef = $data['merchant_ref'] ?? '';
            $status = strtoupper($data['status'] ?? '');
            
            \Log::info('📊 CALLBACK DATA PARSED', [
                'reference' => $reference,
                'merchant_ref' => $merchantRef,
                'status' => $status
            ]);
            
            if (empty($reference) && empty($merchantRef)) {
                \Log::error('❌ NO VALID REFERENCE OR MERCHANT_REF TO QUERY');
                return;
            }
            
            // ✅ FIX: Hapus .kategori dari eager loading
            $payment = Payment::where('reference', $reference)
                             ->orWhere('merchant_ref', $merchantRef)
                             ->first();
            
            if ($payment) {
                \Log::info('✅ PAYMENT FOUND', [
                    'payment_id' => $payment->id,
                    'current_status' => $payment->status,
                    'new_status' => $status,
                    'reference' => $payment->reference,
                    'merchant_ref' => $payment->merchant_ref,
                    'is_plan_payment' => !is_null($payment->plan_id),
                    'is_order_payment' => !is_null($payment->pesanan_id)
                ]);
                
                // VALIDASI STATUS
                if (empty($status) || !in_array($status, ['PAID', 'UNPAID', 'EXPIRED', 'FAILED', 'PENDING'])) {
                    \Log::warning('⚠️ INVALID STATUS FROM CALLBACK', [
                        'status' => $status,
                        'payment_id' => $payment->id
                    ]);
                    return;
                }
                
                // Update payment
                $updateData = ['status' => $status];
                
                if (!empty($data['paid_at'])) {
                    $updateData['paid_at'] = date('Y-m-d H:i:s', $data['paid_at']);
                }
                if (isset($data['amount_received'])) {
                    $updateData['amount_received'] = $data['amount_received'];
                }
                if (isset($data['fee_customer'])) {
                    $updateData['fee_customer'] = $data['fee_customer'];
                }
                
                $payment->update($updateData);
                
                // **FORCE REFRESH DARI DATABASE**
                $payment->refresh();

                \Log::info('🔄 PAYMENT STATUS UPDATED & VERIFIED', [
                    'payment_id' => $payment->id,
                    'reference' => $payment->reference,
                    'status_in_db' => $payment->status,
                    'expected_status' => $status,
                    'match' => $payment->status === $status
                ]);

                // ✅ HANDLE PLAN PAYMENT - CREATE/ACTIVATE SUBSCRIPTION JIKA PAID
                if ($payment->plan_id && $status === 'PAID') {
                    \Log::info('🎁 PROCESSING PLAN PAYMENT - CREATING SUBSCRIPTION', [
                        'payment_id' => $payment->id,
                        'plan_id' => $payment->plan_id,
                        'user_id' => $payment->user_id
                    ]);
                    
                    try {
                        $plan = Plan::find($payment->plan_id);
                        if ($plan) {
                            // Cek jika sudah ada subscription aktif untuk plan ini
                            $existingActive = Subscription::where('user_id', $payment->user_id)
                                ->where('plan_id', $plan->id)
                                ->where('status', 'aktif')
                                ->where('tanggal_berakhir', '>', now())
                                ->first();

                            if (!$existingActive) {
                                // Cek subscription nonaktif yang bisa di-reactivate
                                $inactiveSubscription = Subscription::where('user_id', $payment->user_id)
                                    ->where('plan_id', $plan->id)
                                    ->where('status', 'nonaktif')
                                    ->orderByDesc('created_at')
                                    ->first();

                                if ($inactiveSubscription) {
                                    // Update existing inactive subscription
                                    $inactiveSubscription->update([
                                        'tanggal_mulai' => now(),
                                        'tanggal_berakhir' => now()->addMonths($plan->durasi_bulan),
                                        'status' => 'aktif',
                                    ]);
                                    
                                    \Log::info('✅ SUBSCRIPTION REACTIVATED', [
                                        'subscription_id' => $inactiveSubscription->id,
                                        'plan_id' => $plan->id,
                                        'user_id' => $payment->user_id,
                                        'tanggal_mulai' => $inactiveSubscription->tanggal_mulai,
                                        'tanggal_berakhir' => $inactiveSubscription->tanggal_berakhir
                                    ]);
                                } else {
                                    // Create new subscription
                                    $newSubscription = Subscription::create([
                                        'user_id' => $payment->user_id,
                                        'plan_id' => $plan->id,
                                        'tanggal_mulai' => now(),
                                        'tanggal_berakhir' => now()->addMonths($plan->durasi_bulan),
                                        'status' => 'aktif',
                                    ]);
                                    
                                    \Log::info('✅ NEW SUBSCRIPTION CREATED', [
                                        'subscription_id' => $newSubscription->id,
                                        'plan_id' => $plan->id,
                                        'user_id' => $payment->user_id,
                                        'tanggal_mulai' => $newSubscription->tanggal_mulai,
                                        'tanggal_berakhir' => $newSubscription->tanggal_berakhir,
                                        'durasi_bulan' => $plan->durasi_bulan
                                    ]);
                                }
                            } else {
                                \Log::info('ℹ️ ACTIVE SUBSCRIPTION ALREADY EXISTS', [
                                    'subscription_id' => $existingActive->id,
                                    'tanggal_berakhir' => $existingActive->tanggal_berakhir
                                ]);
                            }
                        } else {
                            \Log::error('❌ PLAN NOT FOUND', ['plan_id' => $payment->plan_id]);
                        }
                    } catch (\Exception $planError) {
                        \Log::error('❌ ERROR CREATING SUBSCRIPTION: ' . $planError->getMessage(), [
                            'payment_id' => $payment->id,
                            'plan_id' => $payment->plan_id,
                            'trace' => $planError->getTraceAsString()
                        ]);
                    }
                }

                // ✅ TRACK KE GA4 JIKA STATUS PAID
                if ($status === 'PAID') {
                    \Log::info('🎯 TRIGGERING GA4 TRACKING FOR PAID PAYMENT', [
                        'payment_id' => $payment->id,
                        'reference' => $payment->reference,
                        'amount' => $payment->amount
                    ]);
                    
                    // Load pesanan dengan relationships untuk order payment
                    if ($payment->pesanan_id) {
                        $payment->load(['pesanan.details.variasiProduk.produk']);
                    }
                    
                    try {
                        $this->trackPurchase($payment, $payment->pesanan);
                    } catch (\Exception $gaError) {
                        \Log::error('❌ GA4 TRACKING ERROR: ' . $gaError->getMessage());
                    }
                }

                // ✅ UPDATE ORDER JIKA ADA (untuk order payments, bukan plan payments)
                if ($payment->pesanan_id) {
                    $order = $payment->pesanan;
                    if ($order) {
                        $statusMap = [
                            'PAID' => 'diproses',
                            'EXPIRED' => 'dibatalkan', 
                            'FAILED' => 'gagal',
                            'UNPAID' => 'menunggu_pembayaran',
                            'PENDING' => 'menunggu_pembayaran'
                        ];
                        
                        $newStatus = $statusMap[$status] ?? $order->status;
                        $order->update(['status' => $newStatus]);
                        $order->refresh();
                        
                        \Log::info('✅ ORDER UPDATED', [
                            'order_id' => $order->id,
                            'new_status' => $newStatus,
                            'verified' => $order->status === $newStatus
                        ]);
                    }
                } else {
                    \Log::info('ℹ️ NO RELATED ORDER FOR THIS PAYMENT (PLAN PAYMENT)', [
                        'payment_id' => $payment->id,
                        'plan_id' => $payment->plan_id
                    ]);
                }
                
                \Log::info('🎉 PAYMENT CALLBACK PROCESSED SUCCESSFULLY');
            } else {
                \Log::error('❌ PAYMENT NOT FOUND IN DATABASE', [
                    'reference' => $reference,
                    'merchant_ref' => $merchantRef,
                    'searched_query' => "WHERE reference='{$reference}' OR merchant_ref='{$merchantRef}'"
                ]);
            }
            
        } catch (\Exception $e) {
            \Log::error('💥 BACKGROUND PROCESSING ERROR: ' . $e->getMessage(), [
                'exception_type' => get_class($e),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    // Method untuk test signature generation
    public function testSignature(Request $request)
    {
        try {
            $merchantCode = config('tripay.merchant_code');
            $privateKey = config('tripay.private_key');
            $merchantRef = 'INV-TEST-' . time();
            $amount = 100000;

            $signatureString = $merchantCode . $merchantRef . $amount;
            $signature = hash_hmac('sha256', $signatureString, $privateKey);

            return response()->json([
                'success' => true,
                'data' => [
                    'merchant_code' => $merchantCode,
                    'merchant_ref' => $merchantRef,
                    'amount' => $amount,
                    'signature_string' => $signatureString,
                    'signature' => $signature,
                    'private_key_first_10_chars' => substr($privateKey, 0, 10) . '...',
                    'private_key_length' => strlen($privateKey)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Method untuk mendapatkan channel pembayaran
    public function getPaymentChannels()
    {
        try {
            Log::info('Getting payment channels from Tripay');
            
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
            
            Log::info('Direct Tripay Channels Response:', $data);
            
            // Filter hanya channel yang aktif
            $activeChannels = [];
            if (isset($data['data']) && is_array($data['data'])) {
                $activeChannels = array_filter($data['data'], function($channel) {
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
            Log::error('Get Payment Channels Error: ' . $e->getMessage());
            return $this->getStaticPaymentChannels();
        }
    }

    // Method fallback ke data static
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
            'note' => 'Using static fallback data'
        ]);
    }

    public function paymentReturn(Request $request)
    {
        // Accept multiple query keys Tripay may send
        $reference = $request->query('reference') 
            ?? $request->query('tripay_reference') 
            ?? $request->query('tripay_ref') 
            ?? null;
        $merchantRef = $request->query('tripay_merchant_ref') 
            ?? $request->query('merchant_ref') 
            ?? null;

        try {
            // Prefer lookup by reference, fallback to merchant_ref if reference unavailable
            if ($reference) {
                // ✅ FIX: Hapus .kategori
                $payment = Payment::with(['pesanan.details.variasiProduk.produk'])
                                ->where('reference', $reference)
                                ->firstOrFail();
            } elseif ($merchantRef) {
                // ✅ FIX: Hapus .kategori
                $payment = Payment::with(['pesanan.details.variasiProduk.produk'])
                                ->where('merchant_ref', $merchantRef)
                                ->firstOrFail();
            } else {
                throw new \Exception('No reference or merchant_ref provided by gateway');
            }
            $order = $payment->pesanan;
             
             // Check payment status using direct API call
             $apiKey = config('tripay.api_key');
             $mode = config('tripay.mode', 'sandbox');
             
             $baseUrl = $mode === 'production' 
                 ? 'https://tripay.co.id/api/' 
                 : 'https://tripay.co.id/api-sandbox/';
             
             $client = new \GuzzleHttp\Client([
                 'force_ip_resolve' => 'v4'
             ]);
             $response = $client->get($baseUrl . 'transaction/detail?reference=' . $reference, [
                 'headers' => [
                     'Authorization' => 'Bearer ' . $apiKey,
                 ]
             ]);
             
             $data = json_decode($response->getBody(), true);
             
             if ($data['success']) {
                 $paymentStatus = $data['data']['status'];
                 
                 // Update payment status
                 $payment->update([
                     'status' => $paymentStatus
                 ]);
                 
                 // ✅ TRACK KE GA4 JIKA STATUS PAID
                 if ($paymentStatus === 'PAID') {
                     $this->trackPurchase($payment, $order);
                 }
                 
                 // Update order status if order exists
                 if ($order) {
                     if ($paymentStatus === 'PAID') {
                         $order->update(['status' => 'dalam_proses']);
                     }
                 } else {
                     \Log::info('No related order for payment, skip order status update', ['reference' => $reference]);
                 }
                 
                 return view('payment.status', compact('payment', 'order'));
             }
             
         } catch (\Exception $e) {
             Log::error('Tripay Status Check Error: ' . $e->getMessage());
         }
         
         return redirect('/permainan')->with('error', 'Gagal memeriksa status pembayaran');
     }

    public function checkStatus(Request $request)
    {
        $request->validate([
            'reference' => 'required'
        ]);

        try {
            $apiKey = config('tripay.api_key');
            $mode = config('tripay.mode', 'sandbox');
            
            $baseUrl = $mode === 'production' 
                ? 'https://tripay.co.id/api/' 
                : 'https://tripay.co.id/api-sandbox/';
            
            $client = new \GuzzleHttp\Client([
                'force_ip_resolve' => 'v4'
            ]);
            $response = $client->get($baseUrl . 'transaction/detail?reference=' . $request->reference, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            if ($data['success']) {
                // ✅ FIX: Hapus .kategori
                $payment = Payment::with(['pesanan.details.variasiProduk.produk'])
                                ->where('reference', $request->reference)
                                ->first();
                
                if ($payment) {
                    $newStatus = $data['data']['status'];
                    $oldStatus = $payment->status;
                    
                    $payment->update(['status' => $newStatus]);
                    
                    // ✅ TRACK JIKA BERUBAH MENJADI PAID
                    if ($oldStatus !== 'PAID' && $newStatus === 'PAID') {
                        $this->trackPurchase($payment, $payment->pesanan);
                    }
                    
                    // Update order status if paid and order exists
                    if ($payment->pesanan && $newStatus === 'PAID') {
                        $payment->pesanan->update(['status' => 'dalam_proses']);
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'status' => $data['data']['status'],
                    'payment' => $payment
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Tripay Status Check Error: ' . $e->getMessage());
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal memeriksa status pembayaran'
        ], 400);
    }

    // Method untuk manual check status (bisa dipanggil via cron job)
    public function syncPaymentStatus($reference = null)
    {
        try {
            if ($reference) {
                // ✅ FIX: Hapus .kategori
                $payments = Payment::with(['pesanan.details.variasiProduk.produk'])
                             ->where('reference', $reference)->get();
            } else {
                // Ambil payments yang statusnya belum final OR sudah lewat expired_time secara lokal
                $payments = Payment::with(['pesanan.details.variasiProduk.produk'])
                    ->where(function($q) {
                        $q->whereIn('status', ['UNPAID', 'PENDING'])
                          ->orWhere(function($q2) {
                              $q2->whereNotIn('status', ['PAID','EXPIRED','FAILED'])
                                 ->whereNotNull('expired_time')
                                 ->where('expired_time', '<', now());
                          });
                    })->get();
            }

            $apiKey = config('tripay.api_key');
            $mode = config('tripay.mode', 'sandbox');

            $baseUrl = $mode === 'production' 
                ? 'https://tripay.co.id/api/' 
                : 'https://tripay.co.id/api-sandbox/';

            $client = new \GuzzleHttp\Client([
                'force_ip_resolve' => 'v4'
            ]);
            $updatedCount = 0;

            foreach ($payments as $payment) {
                try {
                    // Jika payment sudah lewat expired_time secara lokal dan belum berstatus final -> anggap EXPIRED
                    $locallyExpired = false;
                    if ($payment->expired_time) {
                        try {
                            $expiredAt = \Carbon\Carbon::parse($payment->expired_time);
                            if ($expiredAt->isPast() && !in_array($payment->status, ['PAID','EXPIRED','FAILED'])) {
                                $locallyExpired = true;
                            }
                        } catch (\Exception $e) {
                            // parsing error -> ignore local expiration for this record
                        }
                    }

                    if ($locallyExpired) {
                        $tripayStatus = 'EXPIRED';
                        \Log::info('Local expiry detected, marking payment as EXPIRED', ['reference' => $payment->reference]);
                    } else {
                        // Panggil Tripay untuk status terbaru
                        $response = $client->get($baseUrl . 'transaction/detail?reference=' . $payment->reference, [
                            'headers' => [
                                'Authorization' => 'Bearer ' . $apiKey,
                            ],
                            'timeout' => 10
                        ]);

                        $data = json_decode($response->getBody(), true);
                        if (!isset($data['success']) || !$data['success'] || !isset($data['data']['status'])) {
                            \Log::warning('Tripay returned invalid status for payment sync', ['reference' => $payment->reference, 'response' => $data ?? null]);
                            continue;
                        }

                        $tripayStatus = strtoupper($data['data']['status']);
                    }

                    if ($tripayStatus && $payment->status !== $tripayStatus) {
                        $oldStatus = $payment->status;
                        $payment->update(['status' => $tripayStatus]);

                        // ✅ TRACK JIKA BERUBAH MENJADI PAID
                        if ($oldStatus !== 'PAID' && $tripayStatus === 'PAID') {
                            $this->trackPurchase($payment, $payment->pesanan);
                        }

                        // Update order status if ada order terkait
                        $order = $payment->pesanan;
                        if ($order) {
                            if ($tripayStatus === 'PAID') {
                                $order->update(['status' => 'dalam_proses']);
                            } elseif ($tripayStatus === 'EXPIRED') {
                                // jika payment expired => batalkan pesanan
                                $order->update(['status' => 'dibatalkan']);
                            } elseif ($tripayStatus === 'FAILED') {
                                $order->update(['status' => 'gagal']);
                            }
                        } else {
                            \Log::info('No related order when syncing payment, skipped order update', ['reference' => $payment->reference]);
                        }

                        $updatedCount++;
                        Log::info('Payment status synced:', [
                            'reference' => $payment->reference,
                            'old_status' => $oldStatus,
                            'new_status' => $tripayStatus
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Error syncing payment ' . $payment->reference . ': ' . $e->getMessage());
                    continue;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Synced {$updatedCount} payments"
            ]);

        } catch (\Exception $e) {
            Log::error('Sync Payment Status Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function showUploadBukti($reference)
    {
        $payment = Payment::where('reference', $reference)->firstOrFail();
        return view('payment.upload-bukti', compact('payment'));
    }

    public function uploadBukti(Request $request, $reference)
    {
        $request->validate([
            'bukti' => 'required|image|max:2048'
        ]);

        // ✅ FIX: Hapus .kategori
        $payment = Payment::with(['pesanan.details.variasiProduk.produk'])
                         ->where('reference', $reference)
                         ->firstOrFail();
        
        try {
            // Upload file
            $path = $request->file('bukti')->store('bukti-transfer', 'public');
            
            // Update payment
            $payment->update([
                'bukti_transfer' => $path,
                'status' => 'PAID'
            ]);

            // ✅ TRACK KE GA4
            $this->trackPurchase($payment, $payment->pesanan);

            // Notify admin (log) so admin can process the order
            \Log::info('Bukti pembayaran diupload - mohon proses pesanan oleh admin', [
                'payment_id' => $payment->id,
                'pesanan_id' => $payment->pesanan_id,
                'user_id' => $payment->user_id,
                'reference' => $payment->reference,
                'bukti_path' => $path
            ]);

            // Update related records based on payment type
            if ($payment->pesanan_id) {
                // Update order status
                $payment->pesanan->update(['status' => 'dalam_proses']);
            } elseif ($payment->plan_id) {
                // Create/activate subscription ONLY HERE
                $plan = Plan::find($payment->plan_id);
                if ($plan) {
                    // Cek jika sudah ada subscription nonaktif, update jadi aktif, jika belum ada, buat baru
                    $existing = \App\Models\Subscription::where('user_id', $payment->user_id)
                        ->where('plan_id', $plan->id)
                        ->where('status', 'nonaktif')
                        ->orderByDesc('created_at')
                        ->first();
                    if ($existing) {
                        $existing->update([
                            'tanggal_mulai' => now(),
                            'tanggal_berakhir' => now()->addMonths($plan->durasi_bulan),
                            'status' => 'aktif',
                        ]);
                    } else {
                        \App\Models\Subscription::create([
                            'user_id' => $payment->user_id,
                            'plan_id' => $plan->id,
                            'tanggal_mulai' => now(),
                            'tanggal_berakhir' => now()->addMonths($plan->durasi_bulan),
                            'status' => 'aktif',
                        ]);
                    }
                }
            }

            // Redirect user away from upload page with success message.
            $redirectRoute = $payment->pesanan_id
                ? route('order.success', $payment->pesanan_id)
                : route('pesanan.index');

            return redirect($redirectRoute)->with('success', 'Bukti pembayaran berhasil diupload. Tim admin akan memproses pesanan Anda.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal upload bukti pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * TEST METHOD untuk tracking GA4 - DIPERBAIKI
     */
    public function testGATracking(Request $request)
    {
        try {
            // ✅ FIX: Hapus .kategori
            $payment = Payment::with(['pesanan.details.variasiProduk.produk'])
                            ->where('status', 'PAID')
                            ->latest()
                            ->first();
            
            if (!$payment) {
                return response()->json(['error' => 'No paid payment found'], 404);
            }
            
            \Log::info('🧪 TESTING GA4 TRACKING MANUALLY', [
                'payment_id' => $payment->id,
                'reference' => $payment->reference,
                'amount' => $payment->amount
            ]);
            
            $this->trackPurchase($payment, $payment->pesanan);
            
            return response()->json([
                'success' => true,
                'message' => 'GA4 tracking triggered',
                'payment' => [
                    'reference' => $payment->reference,
                    'amount' => $payment->amount,
                    'status' => $payment->status
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('💥 TEST GA4 TRACKING ERROR: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * TEST METHOD untuk cek config GA4 - DIPERBAIKI
     */
    public function testGA4Config()
    {
        $measurementId = config('services.ga4.measurement_id');
        $apiSecret = config('services.ga4.api_secret');
        
        $result = [
            'ga4_configured' => !empty($measurementId) && !empty($apiSecret),
            'measurement_id' => $measurementId ? substr($measurementId, 0, 10) . '...' : 'NOT SET',
            'api_secret' => $apiSecret ? substr($apiSecret, 0, 10) . '...' : 'NOT SET',
            'full_measurement_id' => $measurementId, // Hati-hati di production!
            'api_secret_length' => strlen($apiSecret),
        ];

        \Log::info('🔧 GA4 CONFIG CHECK', $result);
        
        return response()->json($result);
    }

    /**
     * METHOD BARU: Cek semua payments yang PAID tapi belum di-track
     */
    public function checkUntrackedPayments()
    {
        try {
            $paidPayments = Payment::with(['pesanan.details.variasiProduk.produk'])
                                ->where('status', 'PAID')
                                ->where('amount', '>', 0)
                                ->get();

            $result = [
                'total_paid_payments' => $paidPayments->count(),
                'payments' => []
            ];

            foreach ($paidPayments as $payment) {
                $result['payments'][] = [
                    'id' => $payment->id,
                    'reference' => $payment->reference,
                    'amount' => $payment->amount,
                    'created_at' => $payment->created_at,
                    'user_id' => $payment->user_id,
                    'has_order' => !is_null($payment->pesanan)
                ];
            }

            \Log::info('📊 UNTRACKED PAYMENTS CHECK', $result);

            return response()->json($result);

        } catch (\Exception $e) {
            \Log::error('Error checking untracked payments: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * METHOD BARU: Manual trigger tracking untuk payment tertentu
     */
    public function manualTrackPayment(Request $request)
    {
        $request->validate([
            'reference' => 'required|exists:payments,reference'
        ]);

        try {
            $payment = Payment::with(['pesanan.details.variasiProduk.produk'])
                            ->where('reference', $request->reference)
                            ->firstOrFail();

            \Log::info('🔄 MANUAL TRACKING TRIGGERED', [
                'payment_id' => $payment->id,
                'reference' => $payment->reference,
                'current_status' => $payment->status
            ]);

            if ($payment->status === 'PAID') {
                $this->trackPurchase($payment, $payment->pesanan);
                return response()->json([
                    'success' => true,
                    'message' => 'Manual tracking completed',
                    'payment' => $payment
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment is not PAID, cannot track',
                    'status' => $payment->status
                ], 400);
            }

        } catch (\Exception $e) {
            \Log::error('Manual tracking error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}