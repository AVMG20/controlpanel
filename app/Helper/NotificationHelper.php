<?php

namespace App\Helper;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class NotificationHelper
{
    public function __construct()
    {
    }

    /**
     * Send notification using extra checks
     * @param Collection|array|mixed $notifiables
     * @param  $notification
     * @return void
     */
    public function send(mixed $notifiables, $notification)
    {


        Notification::send($notifiables, $notification);
    }
}
