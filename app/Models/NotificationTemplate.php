<?php

namespace App\Models;

use App\Notifications\DynamicNotification;
use App\Settings\MailSettings;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class NotificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'content',
        'channels',
        'disabled',
    ];

    protected $casts = [
        'disabled' => 'bool',
    ];

    /**
     * Send the notification to the given notifiable user.
     *
     * @param User $notifiable The notifiable user
     * @param string $notificationName the name of the notification
     * @param array $data array of variables to be passed to the notification
     * @return bool true if the notification was sent, false otherwise
     */
    public static function sendNotification(User $notifiable, string $notificationName, array $data = [], bool $sendNow = false): bool
    {
        $notificationTemplate = NotificationTemplate::getNotificationByName($notificationName);

        if (is_null($notificationTemplate)) return false;

        $dynamicNotification = $notificationTemplate->getDynamicNotification($data);

        if ($sendNow) $notifiable->notifyNow($dynamicNotification);
        else $notifiable->notify($dynamicNotification);

        return true;
    }

    /**
     * @param string $string
     * @return NotificationTemplate|null return null if mail is not enabled or template is disabled
     *
     * @throws ModelNotFoundException<Model>
     */
    public static function getNotificationByName(string $string): ?NotificationTemplate
    {
        /** @var MailSettings $emailSettings */
        $emailSettings = app(MailSettings::class);

        if (!$emailSettings->mail_enabled) return null;

        $notificationTemplate = static::query()->where('name', $string)->firstOrFail();

        if ($notificationTemplate->disabled) return null;

        return $notificationTemplate;
    }


    /**
     * Channels this notification are sent through
     *
     * @return Attribute
     */
    public function channels(): Attribute
    {
        return Attribute::get(function ($value) {
            return json_decode($value, true);
        });
    }

    /**
     * Get the rendered HTML content
     *
     * @param array $variables
     * @return HtmlString
     */
    public function getContentHtml(array $variables): HtmlString
    {
        $content = str_replace('&gt;', '>', $this->content);

        return new HtmlString(Blade::render($content, $variables));
    }

    /**
     * Get the mail message used for the DynamicNotification
     *
     * @param array $variables
     * @return MailMessage
     */
    public function getMailMessage(array $variables): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject($this->title)
            ->line($this->getContentHtml($variables))
            ->greeting((new HtmlString('<span></span>')))
            ->salutation((new HtmlString('<span></span>')));

        $mailMessage->greeting = '';

        return $mailMessage;
    }

    /**
     * get the DynamicNotification
     *
     * @param array $variables
     * @return DynamicNotification
     */
    public function getDynamicNotification(array $variables): DynamicNotification
    {
        return new DynamicNotification($this->channels, $this->getMailMessage($variables));
    }
}
