<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class PickupReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $child;
    public $pickupTime;

    public function __construct($child, $pickupTime)
    {
        $this->child = $child;
        $this->pickupTime = $pickupTime;
    }

    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'))
        ->subject('【保育園】お迎え時間のリマインド')
        ->view('emails.pickup_reminder')
        ->with([
            'child' => $this->child,
            'pickupTime' => $this->pickupTime 
                ? Carbon::parse($this->pickupTime)->format('H時i分') 
                : '未設定',
        ]);
    }
}
