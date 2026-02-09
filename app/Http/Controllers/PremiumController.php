<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Subscription;

class PremiumController extends Controller
{
    /**
     * Cek status premium user
     */
    public function checkPremiumStatus()
    {
        $user = auth()->user();
        
        $activeSubscription = $user->subscriptions()
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', Carbon::now())
            ->where('tanggal_berakhir', '>=', Carbon::now())
            ->with('plan')
            ->first();

        return response()->json([
            'is_premium' => $activeSubscription ? true : false,
            'subscription' => $activeSubscription,
            'days_remaining' => $activeSubscription ? 
                Carbon::parse($activeSubscription->tanggal_berakhir)->diffInDays(Carbon::now()) : 0
        ]);
    }

    /**
     * Halaman tampilan premium
     */
    public function show()
    {
        $user = auth()->user();
        
        // Ambil subscription aktif jika ada
        $activeSubscription = $user->subscriptions()
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', Carbon::now())
            ->where('tanggal_berakhir', '>=', Carbon::now())
            ->with('plan')
            ->first();

        // Ambil semua plans untuk ditampilkan
        $plans = Plan::all();

        // Ambil subscription history
        $subscriptionHistory = $user->subscriptions()
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.premium', [
            'activeSubscription' => $activeSubscription,
            'plans' => $plans,
            'subscriptionHistory' => $subscriptionHistory,
            'daysRemaining' => $activeSubscription ? 
                Carbon::parse($activeSubscription->tanggal_berakhir)->diffInDays(Carbon::now()) : 0
        ]);
    }

    /**
     * Check apakah user bisa akses resource tertentu
     */
    public function canAccess(Request $request, $resourceType = 'book')
    {
        $user = auth()->user();
        
        $hasAccess = $user->subscriptions()
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', Carbon::now())
            ->where('tanggal_berakhir', '>=', Carbon::now())
            ->exists();

        if ($request->expectsJson()) {
            return response()->json(['has_access' => $hasAccess]);
        }

        return $hasAccess;
    }

    /**
     * Upgrade subscription
     */
    public function upgradePlan(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id'
        ]);

        $user = auth()->user();
        $plan = Plan::findOrFail($request->plan_id);

        return response()->json([
            'success' => true,
            'message' => 'Silahkan lanjutkan ke pembayaran',
            'redirect' => route('premium.payment', ['plan_id' => $plan->id])
        ]);
    }

    /**
     * Show upgrade page
     */
    public function showUpgradePage()
    {
        $plans = Plan::all();
        $user = auth()->user();
        
        $activeSubscription = $user->subscriptions()
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', Carbon::now())
            ->where('tanggal_berakhir', '>=', Carbon::now())
            ->with('plan')
            ->first();

        return view('pages.premium-upgrade', [
            'plans' => $plans,
            'activeSubscription' => $activeSubscription
        ]);
    }
}
