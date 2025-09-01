<?php

namespace App\Console\Commands;

use App\Services\BillNotificationService;
use Illuminate\Console\Command;

class SendUpcomingBillNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-upcoming-bill-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for upcoming bills';

    /**
     * Execute the console command.
     */
    public function handle(BillNotificationService $billNotificationService)
    {
        $billNotificationService->sendUpcomingBillNotifications();

        $this->info('Upcoming bill notifications sent successfully!');
    }
}
