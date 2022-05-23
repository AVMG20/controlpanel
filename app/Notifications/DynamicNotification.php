<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DynamicNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $channels;
    protected MailMessage $mailMessage;

    /**
     * Create a new notification instance.
     *
     * @param array $channels
     * @param MailMessage $mailMessage
     */
    public function __construct(array $channels, MailMessage $mailMessage)
    {
        $this->channels = $channels;
        $this->mailMessage = $mailMessage;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via()
    {
        return $this->channels;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return MailMessage
     */
    public function toMail()
    {
        return $this->mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            //
        ];
    }
}
