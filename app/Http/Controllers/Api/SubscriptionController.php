<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use App\Models\CharacterItem;
use App\Models\ChildItem;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\LouvinService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    /**
     * POST /api/subscription/subscribe
     * Membuat invoice QRIS Louvin. Premium aktif setelah webhook settled.
     */
    public function subscribe(Request $request, LouvinService $louvin)
    {
        $request->validate([
            'plan_id' => 'nullable|exists:plans,id',
            'plan_code' => 'nullable|in:weekly,monthly,yearly',
            'child_id' => 'nullable|exists:anaks,id',
        ]);

        $user = $request->user();
        $plan = $this->resolvePlan($request);

        if (!$plan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Paket langganan tidak ditemukan.',
            ], 422);
        }

        if ($request->filled('child_id')) {
            $childBelongsToUser = Anak::where('id', $request->child_id)
                ->where('user_id', $user->id)
                ->exists();

            if (!$childBelongsToUser) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data anak tidak sesuai dengan akun ini.',
                ], 403);
            }
        }

        try {
            $planCode = $request->input('plan_code', $this->planCodeFor($plan));
            $reference = 'CALISTA-' . strtoupper($planCode) . '-' . $user->id . '-' . now()->format('YmdHis') . '-' . strtoupper(str()->random(6));

            $louvinResponse = $louvin->createTransaction([
                'amount' => (int) $plan->harga_jual,
                'payment_type' => 'qris',
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'description' => $plan->nama_paket,
                'reference' => $reference,
            ]);

            $transaction = $louvinResponse['transaction'] ?? [];
            $payment = $louvinResponse['payment'] ?? [];
            $transactionId = $transaction['id'] ?? null;
            $orderId = $payment['order_id'] ?? $transaction['reference'] ?? $reference;

            if (!$transactionId || empty($payment)) {
                Log::warning('Louvin response missing transaction payment data', ['response' => $louvinResponse]);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Louvin tidak mengembalikan data pembayaran.',
                ], 502);
            }

            $paymentRecord = Payment::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'provider' => 'louvin',
                'provider_transaction_id' => $transactionId,
                'provider_subscription_id' => null,
                'provider_payload' => $louvinResponse,
                'merchant_ref' => $orderId,
                'payment_method' => $payment['payment_type'] ?? 'qris',
                'payment_name' => strtoupper($payment['payment_type'] ?? 'qris'),
                'amount' => (int) ($transaction['net_amount'] ?? $plan->harga_jual),
                'fee_merchant' => !($transaction['fee_on_customer'] ?? true) ? (int) ($transaction['fee'] ?? 0) : 0,
                'fee_customer' => ($transaction['fee_on_customer'] ?? true) ? (int) ($transaction['fee'] ?? 0) : 0,
                'total_fee' => (int) ($transaction['fee'] ?? 0),
                'amount_received' => (int) ($transaction['net_amount'] ?? $plan->harga_jual),
                'pay_code' => $payment['qr_string'] ?? $payment['payment_number'] ?? null,
                'pay_url' => $payment['deeplink_url'] ?? null,
                'checkout_url' => $payment['qr_string'] ?? $payment['payment_number'] ?? '',
                'status' => 'UNPAID',
                'expired_time' => isset($payment['expired_at']) ? Carbon::parse($payment['expired_at']) : null,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Invoice Calista berhasil dibuat. Selesaikan pembayaran untuk mengaktifkan paket.',
                'data' => [
                    'payment' => [
                        'id' => $paymentRecord->id,
                        'provider' => 'louvin',
                        'status' => 'pending',
                        'plan_name' => $plan->nama_paket,
                        'amount' => (int) ($transaction['net_amount'] ?? $plan->harga_jual),
                        'fee' => (int) ($transaction['fee'] ?? 0),
                        'total_payment' => (int) ($payment['total_payment'] ?? $transaction['amount'] ?? $plan->harga_jual),
                        'payment_type' => $payment['payment_type'] ?? 'qris',
                        'qr_string' => $payment['qr_string'] ?? null,
                        'payment_number' => $payment['payment_number'] ?? null,
                        'va_number' => $payment['va_number'] ?? null,
                        'bank' => $payment['bank'] ?? null,
                        'expired_at' => $payment['expired_at'] ?? null,
                    ],
                ],
            ], 201);
        } catch (\Exception $e) {
            Log::error('Subscription error', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat invoice Louvin.',
            ], 500);
        }
    }

    public function louvinWebhook(Request $request)
    {
        $webhookToken = config('services.louvin.webhook_token');
        if ($webhookToken && !hash_equals($webhookToken, (string) $request->header('X-Webhook-Token', ''))) {
            Log::warning('Louvin webhook rejected because token is invalid', [
                'event' => $request->input('event'),
                'ip' => $request->ip(),
            ]);

            return response()->json(['received' => false], 401);
        }

        $event = $request->input('event');
        $data = $request->input('data', []);

        try {
            if ($event === 'payment.settled') {
                $payment = $this->findLouvinPaymentFromWebhook($data);

                if ($payment) {
                    $this->activateFromPayment($payment, $data);
                } else {
                    Log::warning('Louvin settled webhook payment not found', ['payload' => $data]);
                }
            }

            if ($event === 'payment.failed') {
                $payment = $this->findLouvinPaymentFromWebhook($data);

                if ($payment && $payment->status !== 'PAID') {
                    $payment->update([
                        'status' => 'FAILED',
                        'provider_payload' => array_merge($payment->provider_payload ?? [], [
                            'failed_webhook' => $data,
                        ]),
                    ]);
                }
            }

            if ($event === 'subscription.renewed') {
                $this->renewFromLouvin($data);
            }
        } catch (\Exception $e) {
            Log::error('Louvin webhook handling failed', [
                'event' => $event,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json(['received' => true]);
    }

    /**
     * GET /api/subscription/status
     * Cek status langganan aktif saat ini.
     */
    public function status(Request $request)
    {
        $user = $request->user();
        $subscription = Subscription::where('user_id', $user->id)
            ->active()
            ->with('plan')
            ->latest('tanggal_berakhir')
            ->first();

        return response()->json([
            'status' => 'success',
            'is_premium' => $subscription !== null,
            'has_ever_subscribed' => $user->hasEverSubscribed(),
            'data' => $subscription ? [
                'plan_name' => $subscription->plan->nama_paket,
                'starts_at' => $subscription->tanggal_mulai,
                'ends_at' => $subscription->tanggal_berakhir,
            ] : null,
        ], 200);
    }

    /**
     * GET /api/subscription/history
     * Mendapatkan riwayat pembayaran/langganan user.
     */
    public function history(Request $request)
    {
        $user = $request->user();
        
        // 1. Ambil data payments riil
        $payments = Payment::where('user_id', $user->id)
            ->whereNotNull('plan_id')
            ->with('plan')
            ->latest()
            ->get();

        // 2. Ambil data subscriptions riil
        $subscriptions = Subscription::where('user_id', $user->id)
            ->with('plan')
            ->latest('tanggal_mulai')
            ->get();

        $data = collect();

        // Map payments riil
        foreach ($payments as $payment) {
            $data->push([
                'id' => $payment->id,
                'plan_name' => $payment->plan ? $payment->plan->nama_paket : 'Calista Plus',
                'amount' => (int) $payment->amount,
                'payment_method' => $payment->payment_name ?? 'QRIS',
                'status' => $payment->status, // PAID, UNPAID, EXPIRED, FAILED
                'status_display' => $payment->status_display,
                'merchant_ref' => $payment->merchant_ref,
                'pay_code' => $payment->pay_code,
                'pay_url' => $payment->pay_url,
                'checkout_url' => $payment->checkout_url,
                'created_at' => $payment->created_at ? $payment->created_at->toIso8601String() : null,
                'expired_time' => $payment->expired_time ? $payment->expired_time->toIso8601String() : null,
                'is_virtual' => false,
            ]);
        }

        // Cari subscriptions yang tidak terwakili oleh payment PAID
        foreach ($subscriptions as $sub) {
            $hasMatchingPaidPayment = $payments->contains(function ($payment) use ($sub) {
                return $payment->plan_id == $sub->plan_id && $payment->status === 'PAID';
            });

            if (!$hasMatchingPaidPayment) {
                $statusDisplay = $sub->tanggal_berakhir > now() ? 'Aktif' : 'Expired';
                $data->push([
                    'id' => 'virtual-' . $sub->id,
                    'plan_name' => $sub->plan ? $sub->plan->nama_paket : 'Calista Plus',
                    'amount' => $sub->plan ? (int) $sub->plan->harga_jual : 0,
                    'payment_method' => 'Sistem (Aktivasi Manual)',
                    'status' => 'PAID',
                    'status_display' => 'Sudah Aktif (' . $statusDisplay . ')',
                    'merchant_ref' => 'SUB-VIRTUAL-' . $sub->id,
                    'pay_code' => null,
                    'pay_url' => null,
                    'checkout_url' => null,
                    'created_at' => $sub->tanggal_mulai ? Carbon::parse($sub->tanggal_mulai)->toIso8601String() : ($sub->created_at ? $sub->created_at->toIso8601String() : null),
                    'expired_time' => $sub->tanggal_berakhir ? Carbon::parse($sub->tanggal_berakhir)->toIso8601String() : null,
                    'is_virtual' => true,
                ]);
            }
        }

        // Urutkan berdasarkan created_at descending
        $sortedData = $data->sortByDesc('created_at')->values()->all();

        return response()->json([
            'status' => 'success',
            'data' => $sortedData
        ], 200);
    }

    private function resolvePlan(Request $request): ?Plan
    {
        if ($request->filled('plan_id')) {
            return Plan::find($request->plan_id);
        }

        $planNames = [
            'weekly' => ['Calista Edu Plan Mingguan', 'Calista Plus Mingguan'],
            'monthly' => ['Calista Edu Plan Bulanan', 'Calista Plus Bulanan'],
            'yearly' => ['Calista Edu Plan Tahunan', 'Calista Plus Tahunan'],
        ];

        $names = $planNames[$request->plan_code] ?? null;

        return $names ? Plan::whereIn('nama_paket', $names)->first() : null;
    }

    private function calculateEndDate(Carbon $startDate, Plan $plan): Carbon
    {
        if (str_contains(strtolower($plan->nama_paket), 'mingguan')) {
            return $startDate->copy()->addDays(7);
        }

        return $startDate->copy()->addMonths(max(1, (int) $plan->durasi_bulan));
    }

    private function activateFromPayment(Payment $payment, array $webhookData): void
    {
        DB::transaction(function () use ($payment, $webhookData) {
            $payment = Payment::whereKey($payment->id)
                ->lockForUpdate()
                ->with(['user', 'plan'])
                ->first();

            if (!$payment) {
                return;
            }

            if ($payment->status === 'PAID') {
                return;
            }

            if (!$payment->plan) {
                Log::warning('Louvin settled webhook ignored because payment has no plan', [
                    'payment_id' => $payment->id,
                    'payload' => $webhookData,
                ]);
                return;
            }

            $paidAmount = (int) ($webhookData['net_amount'] ?? $webhookData['amount'] ?? 0);
            if ($paidAmount > 0 && $paidAmount < (int) $payment->amount) {
                Log::warning('Louvin settled webhook ignored because amount is lower than invoice', [
                    'payment_id' => $payment->id,
                    'expected_amount' => (int) $payment->amount,
                    'paid_amount' => $paidAmount,
                ]);
                return;
            }

            $payment->update([
                'status' => 'PAID',
                'provider_payload' => array_merge($payment->provider_payload ?? [], [
                    'settled_webhook' => $webhookData,
                ]),
            ]);

            $periodEnd = $payment->provider_payload['subscription']['current_period_end'] ?? null;
            $endDate = $periodEnd
                ? Carbon::parse($periodEnd)
                : $this->calculateEndDate(now(), $payment->plan);

            Subscription::where('user_id', $payment->user_id)
                ->where('status', 'aktif')
                ->update(['status' => 'nonaktif']);

            Subscription::create([
                'user_id' => $payment->user_id,
                'plan_id' => $payment->plan_id,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => $endDate,
                'status' => 'aktif',
            ]);

            $this->awardSubscriptionOutfits($payment->user_id, $payment->plan);
        });
    }

    private function findLouvinPaymentFromWebhook(array $data): ?Payment
    {
        $transactionId = $data['transaction_id'] ?? null;
        $orderId = $data['order_id'] ?? null;

        if (!$transactionId && !$orderId) {
            Log::warning('Louvin webhook ignored because identifiers are missing', ['payload' => $data]);
            return null;
        }

        return Payment::where('provider', 'louvin')
            ->where(function ($query) use ($transactionId, $orderId) {
                if ($transactionId) {
                    $query->where('provider_transaction_id', $transactionId);
                }

                if ($orderId) {
                    $method = $transactionId ? 'orWhere' : 'where';
                    $query->{$method}('merchant_ref', $orderId);
                }
            })
            ->with(['user', 'plan'])
            ->first();
    }

    private function renewFromLouvin(array $data): void
    {
        $payment = Payment::where('provider', 'louvin')
            ->where('provider_subscription_id', $data['subscription_id'] ?? null)
            ->with(['user', 'plan'])
            ->latest()
            ->first();

        $user = $payment?->user ?? User::where('email', $data['customer_email'] ?? null)->first();
        $plan = $payment?->plan ?? $this->planFromLouvinPlanId($data['plan_id'] ?? null);

        if (!$user || !$plan) {
            Log::warning('Louvin renewal ignored because user or plan was not found', ['payload' => $data]);
            return;
        }

        DB::transaction(function () use ($user, $plan, $data) {
            Subscription::where('user_id', $user->id)
                ->where('status', 'aktif')
                ->update(['status' => 'nonaktif']);

            Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => Carbon::parse($data['new_period_end'] ?? now()->addMonth()),
                'status' => 'aktif',
            ]);

            $this->awardSubscriptionOutfits($user->id, $plan);
        });
    }

    private function awardSubscriptionOutfits(int $userId, Plan $plan): int
    {
        $planName = strtolower($plan->nama_paket);
        $itemQuery = CharacterItem::query();

        if (str_contains($planName, 'mingguan')) {
            $itemQuery->where('name', 'Nusa Panda Scout');
        } else {
            $itemQuery->where('unlock_type', 'premium');
        }

        $items = $itemQuery->get();
        $children = Anak::where('user_id', $userId)->get();
        $awardedCount = 0;

        foreach ($children as $child) {
            foreach ($items as $item) {
                $exists = ChildItem::where('anak_id', $child->id)
                    ->where('character_item_id', $item->id)
                    ->exists();

                if ($exists) {
                    continue;
                }

                ChildItem::create([
                    'anak_id' => $child->id,
                    'character_item_id' => $item->id,
                    'is_equipped' => false,
                    'unlocked_at' => Carbon::now(),
                ]);

                $awardedCount++;
            }
        }

        return $awardedCount;
    }

    private function planCodeFor(Plan $plan): string
    {
        $name = strtolower($plan->nama_paket);

        if (str_contains($name, 'mingguan')) {
            return 'weekly';
        }

        if (str_contains($name, 'tahunan')) {
            return 'yearly';
        }

        return 'monthly';
    }

    private function planFromLouvinPlanId(?string $louvinPlanId): ?Plan
    {
        $plans = array_flip(config('services.louvin.plans'));
        $planCode = $plans[$louvinPlanId] ?? null;

        if (!$planCode) {
            return null;
        }

        $request = new Request(['plan_code' => $planCode]);

        return $this->resolvePlan($request);
    }
}
