<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\UserController;

class SendPushNotifications extends Command
{
    protected $signature = 'send:push-notifications';
    protected $description = 'Send push notifications 1 hour before scheduled pickup time';

    public function handle()
    {
        app(UserController::class)->sendPushNotifications();
    }
}
