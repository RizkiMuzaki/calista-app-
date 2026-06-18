<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Send email using Resend API or fallback to Laravel Mail driver.
     */
    public static function send($to, $subject, $htmlContent)
    {
        $apiKey = env('RESEND_API_KEY');
        
        Log::info("Sending email to: {$to}", [
            'subject' => $subject,
            'has_resend_key' => !empty($apiKey),
        ]);

        if (empty($apiKey)) {
            // Fallback to Laravel Mail log driver (writes to storage/logs/laravel.log)
            try {
                $fromAddress = env('MAIL_FROM_ADDRESS', 'noreply@calistamobile.com');
                $fromName = env('MAIL_FROM_NAME', 'Calista Mobile');

                Mail::html($htmlContent, function ($message) use ($to, $subject, $fromAddress, $fromName) {
                    $message->to($to)
                        ->subject($subject)
                        ->from($fromAddress, $fromName)
                        ->replyTo('calista.eduapp@gmail.com', 'Calista Support');
                });
                return true;
            } catch (\Exception $e) {
                Log::error("Laravel Mail log fallback failed: " . $e->getMessage());
                return false;
            }
        }

        try {
            $fromAddress = env('MAIL_FROM_ADDRESS', 'onboarding@resend.dev');
            $fromName = env('MAIL_FROM_NAME', 'Calista Mobile');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(10)->post('https://api.resend.com/emails', [
                'from' => "{$fromName} <{$fromAddress}>",
                'to' => [$to],
                'subject' => $subject,
                'html' => $htmlContent,
                'reply_to' => 'calista.eduapp@gmail.com',
            ]);

            if ($response->successful()) {
                Log::info("Email successfully sent via Resend API to {$to}");
                return true;
            }

            Log::error("Resend API failed: " . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error("Exception while sending email via Resend: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send OTP verification email for registration.
     */
    public static function sendOtpEmail($to, $otp)
    {
        $subject = "Kode Verifikasi Calista Mobile";
        $html = view('emails.otp', ['otp' => $otp])->render();
        return self::send($to, $subject, $html);
    }

    /**
     * Send OTP email for forgot password.
     */
    public static function sendForgotPasswordOtpEmail($to, $otp)
    {
        $subject = "Atur Ulang Kata Sandi Calista Mobile";
        $html = view('emails.forgot_password_otp', ['otp' => $otp])->render();
        return self::send($to, $subject, $html);
    }

    /**
     * Send Welcome Email after successful verification.
     */
    public static function sendWelcomeEmail($to, $name)
    {
        $subject = "Selamat Datang di Calista Mobile! 🎉";
        $html = view('emails.welcome', ['name' => $name])->render();
        return self::send($to, $subject, $html);
    }

    /**
     * Send Weekly/Monthly Learning Progress Report Email.
     */
    public static function sendWeeklyReportEmail($to, $parentName, $anak, $isManual = false, $period = 'week')
    {
        $days = $period === 'month' ? 30 : 7;
        $start = now()->subDays($days)->startOfDay();
        $end = now()->endOfDay();

        // Get sessions
        $sessions = \App\Models\PlaySession::where('anak_id', $anak->id)
            ->whereBetween('played_on', [$start->toDateString(), $end->toDateString()])
            ->with(['level.module', 'module'])
            ->get();

        $totalSessions = $sessions->count();
        $hasSessions = $totalSessions > 0;

        $completedSessions = $sessions->where('status', 'completed');
        $scoreSessions = $completedSessions->where('score', '>', 0);
        $totalPlaySeconds = $sessions->sum('duration_seconds');
        $totalPlayMinutes = round($totalPlaySeconds / 60, 1);
        $avgScore = $scoreSessions->count() > 0 ? round($scoreSessions->avg('score')) : 0;
        $earnedStars = $completedSessions->sum('bintang');

        $vak = self::_calculateVakFromSessions($sessions);
        $recommendations = self::_generateWeeklyRecommendations($sessions, $vak, $period);

        $periodLabel = $period === 'month' ? 'Bulanan' : 'Mingguan';
        $statLabel = $period === 'month' ? 'Bulan Ini' : 'Minggu Ini';

        if ($isManual) {
            $subject = "Laporan Belajar Anak: {$anak->nama_anak} (Dikirim Ulang) 📊";
        } else {
            $subject = "Laporan Belajar {$periodLabel} Calista Plus: {$anak->nama_anak} 📊";
        }

        $htmlContent = view('emails.weekly_report', [
            'parentName' => $parentName,
            'anak' => $anak,
            'hasSessions' => $hasSessions,
            'totalSessions' => $totalSessions,
            'totalPlayMinutes' => $totalPlayMinutes,
            'avgScore' => $avgScore,
            'earnedStars' => $earnedStars,
            'vak' => $vak,
            'recommendations' => $recommendations,
            'periodLabel' => $periodLabel,
            'statLabel' => $statLabel,
            'period' => $period,
        ])->render();

        return self::send($to, $subject, $htmlContent);
    }

    private static function _calculateVakFromSessions($sessions)
    {
        $v = 0; $a = 0; $k = 0;
        $sumV = 0; $sumA = 0; $sumK = 0;

        $grouped = $sessions->groupBy(fn ($s) => $s->module_slug ?? 'unknown');
        foreach ($grouped as $slug => $items) {
            $completed = $items->where('status', 'completed');
            $avgScore = $completed->where('score', '>', 0)->avg('score') ?? 0;
            $count = $completed->count();
            if ($avgScore > 0 && $count > 0) {
                switch ($slug) {
                    case 'reading':
                    case 'membaca':
                        $sumV += $avgScore * 0.7;
                        $sumA += $avgScore * 0.3;
                        break;
                    case 'writing':
                    case 'menulis':
                        $sumK += $avgScore * 0.8;
                        $sumV += $avgScore * 0.2;
                        break;
                    case 'counting':
                    case 'berhitung':
                        $sumV += $avgScore * 0.6;
                        $sumK += $avgScore * 0.4;
                        break;
                    case 'puzzle':
                        $sumV += $avgScore * 0.5;
                        $sumK += $avgScore * 0.5;
                        break;
                }
            }
        }

        $total = $sumV + $sumA + $sumK;
        if ($total > 0) {
            $v = (int)round(($sumV / $total) * 100);
            $a = (int)round(($sumA / $total) * 100);
            $k = 100 - ($v + $a);
            $scores = ['Visual' => $v, 'Auditori' => $a, 'Kinestetik' => $k];
            $dominant = array_search(max($scores), $scores);
            return [
                'visual' => $v,
                'auditory' => $a,
                'kinesthetic' => $k,
                'has_data' => true,
                'dominant' => $dominant,
            ];
        }

        return [
            'visual' => 0,
            'auditory' => 0,
            'kinesthetic' => 0,
            'has_data' => false,
            'dominant' => '-',
        ];
    }

    private static function _generateWeeklyRecommendations($sessions, $vak, $period = 'week')
    {
        $recommendations = [];
        $completedTotal = $sessions->where('status', 'completed')->count();

        if ($completedTotal <= 0) {
            return ['Belum ada data bermain. Ajak anak belajar secara konsisten bersama Nusa.'];
        }

        $grouped = $sessions->groupBy(fn ($s) => $s->module_slug ?? 'unknown');
        foreach ($grouped as $slug => $items) {
            $completed = $items->where('status', 'completed');
            $avgScore = $completed->where('score', '>', 0)->avg('score') ?? 0;
            if ($avgScore > 0 && $avgScore < 60) {
                $moduleName = $items->first()->module_name ?? ucfirst($slug);
                $recommendations[] = "Latihan modul {$moduleName} perlu ditingkatkan. Coba ulangi level yang skornya masih rendah.";
            }
        }

        if ($vak['has_data']) {
            if ($vak['kinesthetic'] < 20) {
                $recommendations[] = "Latih motorik halus anak dengan aktivitas menulis dan meniru gerakan nusa.";
            }
            if ($vak['visual'] > 50) {
                $recommendations[] = "Anak cenderung merupakan pembelajar visual. Sering-seringlah membacakan dongeng bergambar Calista.";
            }
            if ($vak['auditory'] > 50) {
                $recommendations[] = "Anak sangat suka belajar lewat audio. Ajak bernyanyi dan mendengarkan Nusa Tutor membaca dongeng.";
            }
        }

        if (empty($recommendations)) {
            $periodText = $period === 'month' ? 'bulan ini' : 'minggu ini';
            $recommendations[] = "Progres belajar anak {$periodText} sangat baik! Pertahankan konsistensi belajarnya ya Bun 🌟";
        }

        return $recommendations;
    }
}
