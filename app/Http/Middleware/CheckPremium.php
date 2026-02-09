<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckPremium
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Cek apakah user punya subscription aktif
        $activeSubscription = $user->subscriptions()
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', Carbon::now())
            ->where('tanggal_berakhir', '>=', Carbon::now())
            ->first();

        // Jika tidak ada subscription aktif, redirect ke halaman premium
        if (!$activeSubscription) {
            return redirect()->route('premium.show');
        }

        return $next($request);
    }
}
