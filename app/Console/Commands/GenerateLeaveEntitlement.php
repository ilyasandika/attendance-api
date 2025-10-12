<?php

namespace App\Console\Commands;

use App\Services\LeaveEntitlementService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateLeaveEntitlement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-leave-entitlement';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(LeaveEntitlementService $leaveEntitlementService)
    {
        $leaveEntitlementService->generateLeaveEntitlement();
        Log::info('Cron Job Sukses: Generate Leave Entitlement via service dijalankan.');
    }
}
