<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Support\Facades\Log;

class SendWeeklyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-weekly-reports';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Send weekly progress reports to all active Plus Plan users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting sending weekly reports...');
        Log::info('Console: app:send-weekly-reports started.');

        // Ambil semua user dengan relasi anaks
        $users = User::with('anaks')->get();
        
        $count = 0;
        /** @var User $user */
        foreach ($users as $user) {
            // Cek apakah user memiliki langganan aktif
            if ($user->hasActiveSubscription()) {
                foreach ($user->anaks as $anak) {
                    $this->info("Sending report for {$anak->nama_anak} to {$user->email}...");
                    Log::info("Sending weekly report for child {$anak->nama_anak} (ID: {$anak->id}) to {$user->email}");
                    
                    try {
                        $sent = EmailService::sendWeeklyReportEmail($user->email, $user->name, $anak);
                        if ($sent) {
                            $count++;
                        } else {
                            $this->error("Failed to send report for {$anak->nama_anak}.");
                        }
                    } catch (\Exception $e) {
                        $this->error("Error sending email: " . $e->getMessage());
                        Log::error("Error sending weekly email for child ID {$anak->id}: " . $e->getMessage());
                    }
                }
            }
        }

        $this->info("Successfully sent {$count} reports.");
        Log::info("Console: app:send-weekly-reports completed. Sent: {$count}");
        return self::SUCCESS;
    }
}
