<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;

class PushNotification extends Notification
{
    public function via($notifiable)
    {
        return ['webpush'];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title("📢 新着通知")
            ->body("これは Laravel から送られたプッシュ通知です！")
            ->icon('/icon.png')
            ->data(['url' => url('/')]); // クリック時の遷移先
    }
}
