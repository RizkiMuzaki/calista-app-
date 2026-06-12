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

        $subscription = $user->subscriptions()
            ->with('plan')
            ->active()
            ->latest('tanggal_berakhir')
            ->first();

        if (!$subscription || !$subscription->plan) {
            return $this->planConfig('free');
        }

        $name = mb_strtolower($subscription->plan->nama_paket ?? '', 'UTF-8');
        if (str_contains($name, 'mingguan') || str_contains($name, 'weekly')) {
            return $this->planConfig('weekly');
        }

        if (str_contains($name, 'bulanan') || str_contains($name, 'monthly')) {
            return $this->planConfig('monthly');
        }

        return $this->planConfig('free');
    }

    public function canSpend(?User $user, int $credits): array
    {
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
        $guard = $this->canSpend($user, $credits);

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
            'weekly' => now()->startOfWeek(Carbon::MONDAY),
            default => now()->startOfMonth(),
        };
    }

    private function planConfig(string $code): array
    {
        $plans = config('services.calista_ai.credit_plans', []);
        $defaults = [
            'free' => ['code' => 'free', 'limit' => 0, 'reset' => 'monthly'],
            'weekly' => ['code' => 'weekly', 'limit' => 30000, 'reset' => 'weekly'],
            'monthly' => ['code' => 'monthly', 'limit' => 100000, 'reset' => 'monthly'],
        ];

        return array_merge($defaults[$code] ?? $defaults['free'], $plans[$code] ?? []);
    }
}
