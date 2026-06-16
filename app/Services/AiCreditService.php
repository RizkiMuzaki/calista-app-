<?php

namespace App\Services;

use App\Models\AiCreditUsage;
use App\Models\User;
use Carbon\Carbon;

class AiCreditService
{
    public function estimateElevenLabsCredits(string $text): int
    {
        return max(1, mb_strlen(trim($text), 'UTF-8'));
    }

    public function planFor(?User $user): array
    {
        if (!$user) {
            return $this->planConfig('free');
        }

        // Admin/developer emails override for unlimited testing
        $adminEmailsEnv = env('CALISTA_ADMIN_EMAILS', 'rizkimzk@gmail.com,calista.eduapp@gmail.com,testbunda@calista.com');
        $adminEmails = array_map('trim', explode(',', $adminEmailsEnv));
        if (in_array(mb_strtolower($user->email, 'UTF-8'), $adminEmails)) {
            $plan = $this->planConfig('monthly');
            $plan['limit'] = 99999999;
            return $plan;
        }

        $subscription = $user->subscriptions()
            ->with('plan')
            ->active()
            ->latest('tanggal_berakhir')
            ->first();

        if (!$subscription || !$subscription->plan) {
            return $this->planConfig('free');
        }

        $durasi = (int) ($subscription->plan->durasi_bulan ?? 0);
        $name = mb_strtolower($subscription->plan->nama_paket ?? '', 'UTF-8');

        // Cek weekly dulu (durasi pendek atau keyword)
        if (str_contains($name, 'mingguan') || str_contains($name, 'weekly')) {
            return $this->planConfig('weekly');
        }
        // Tahunan
        if (str_contains($name, 'tahunan') || str_contains($name, 'yearly') || $durasi >= 12) {
            return $this->planConfig('yearly');
        }
        // Bulanan
        if (str_contains($name, 'bulanan') || str_contains($name, 'monthly') || $durasi >= 1) {
            return $this->planConfig('monthly');
        }

        // Fallback: user aktif berlangganan tapi nama tidak match → monthly (jangan pernah return free)
        return $this->planConfig('monthly');
    }

    public function canSpend(?User $user, int $credits, string $feature = 'nusa_tts'): array
    {
        if ($feature === 'audio_pack_generation') {
            return [
                'allowed' => true,
                'plan' => 'unlimited_onboarding',
                'limit' => 99999999,
                'used' => 0,
                'remaining' => 99999999,
                'requested' => $credits,
                'period_start' => now()->startOfMonth()->toDateString(),
            ];
        }

        $plan = $this->planFor($user);
        $used = $this->usedCredits($user, $plan);
        $remaining = max(0, $plan['limit'] - $used);

        return [
            'allowed' => $credits <= $remaining,
            'plan' => $plan['code'],
            'limit' => $plan['limit'],
            'used' => $used,
            'remaining' => $remaining,
            'requested' => $credits,
            'period_start' => $this->periodStart($plan)->toDateString(),
        ];
    }

    public function recordSpend(?User $user, string $feature, string $text, array $metadata = []): array
    {
        $credits = $this->estimateElevenLabsCredits($text);
        $guard = $this->canSpend($user, $credits, $feature);

        if (!$guard['allowed']) {
            return $guard;
        }

        AiCreditUsage::create([
            'user_id' => $user?->id,
            'plan_code' => $guard['plan'],
            'feature' => $feature,
            'credits' => $credits,
            'characters' => mb_strlen(trim($text), 'UTF-8'),
            'period_start' => $guard['period_start'],
            'metadata' => $metadata,
        ]);

        $guard['used'] += $credits;
        $guard['remaining'] = max(0, $guard['limit'] - $guard['used']);

        return $guard;
    }

    private function usedCredits(?User $user, array $plan): int
    {
        return (int) AiCreditUsage::query()
            ->when($user, fn ($query) => $query->where('user_id', $user->id))
            ->when(!$user, fn ($query) => $query->whereNull('user_id'))
            ->where('plan_code', $plan['code'])
            ->where('period_start', $this->periodStart($plan)->toDateString())
            ->sum('credits');
    }

    private function periodStart(array $plan): Carbon
    {
        return match ($plan['reset']) {
            'weekly' => now()->startOfWeek(1),
            default => now()->startOfMonth(),
        };
    }

    private function planConfig(string $code): array
    {
        $plans = config('services.calista_ai.credit_plans', []);
        $defaults = [
            'free' => ['code' => 'free', 'limit' => 35000, 'reset' => 'monthly'],
            'weekly' => ['code' => 'weekly', 'limit' => 7000, 'reset' => 'weekly'],
            'monthly' => ['code' => 'monthly', 'limit' => 12366, 'reset' => 'monthly'],
            'yearly' => ['code' => 'yearly', 'limit' => 0, 'reset' => 'monthly'],
        ];

        return array_merge($defaults[$code] ?? $defaults['free'], $plans[$code] ?? []);
    }
}
