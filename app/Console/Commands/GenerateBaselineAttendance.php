<?php

namespace App\Console\Commands;

use App\Services\AttendanceService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateBaselineAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-baseline-attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    public function handle(AttendanceService $attendanceService)
    {
        $this->info('Memulai proses generate baseline attendance...');

        try {
            $attendanceService->generateDailyBaseline();

            Log::info('Cron Job Sukses: Generate Baseline Attendance via service dijalankan.');
            $this->info('Proses generate baseline attendance berhasil.');

        } catch (\Exception $e) {
            Log::error('Cron Job Gagal: Generate Baseline Attendance. Error: ' . $e->getMessage());
            $this->error('Terjadi kesalahan saat menjalankan proses.');
        }

        return 0;
    }

}
