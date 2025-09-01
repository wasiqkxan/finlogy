<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\UpcomingBillMail;

class BillNotificationService
{
    public function sendUpcomingBillNotifications()
    {
        $users = User::with(['bills' => function ($query) {
            $query->where('due_date', '=', now()->addDays(3));
        }])->get();

        foreach ($users as $user) {
            foreach ($user->bills as $bill) {
                Mail::to($user->email)->send(new UpcomingBillMail($bill));
            }
        }
    }
}
