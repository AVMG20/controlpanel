<?php

namespace App\Observers;

use App\Models\NotificationTemplate;
use App\Models\User;
use App\Settings\MailSettings;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\Console\Output\ConsoleOutput;

class UserObserver
{

    /**
     * @param MailSettings $settings
     */
    public function __construct(MailSettings $emailSettings)
    {
        $this->emailSettings = $emailSettings;
    }

    /**
     * Handle the User "created" event.
     * Send welcome message to the user
     *
     * @param User $user
     * @return void
     */
    public function created(User $user)
    {
        /** @var NotificationTemplate $notificationTemplate */
        $notificationTemplate = NotificationTemplate::query()
            ->where('name', '=' , 'welcome-message')
            ->firstOrFail();

        if (!$notificationTemplate->disabled && $this->emailSettings->mail_password !== null) {
            $user->notify($notificationTemplate->getDynamicNotification(compact('user')));
        }
    }

    /**
     * Handle the User "updated" event.
     *
     * @param User $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param User $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
