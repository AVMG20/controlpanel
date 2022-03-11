<?php

namespace App\Models;

use App\Notifications\DynamicNotification;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'disabled' => 'bool'
    ];

    public function channels() : Attribute
    {
        return Attribute::get(function ($value) {
            return json_decode(json_decode($value), true);
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
        return (new MailMessage)
            ->subject($this->title)
            ->salutation((new HtmlString('<span></span>')))
            ->line($this->getContentHtml($variables));
    }

    /**
     * get the DynamicNotification
     *
     * @param array $variables
     * @return DynamicNotification
     */
    public function getDynamicNotification(array $variables): DynamicNotification
    {
        return new DynamicNotification($this->channels , $this->getMailMessage($variables));
    }
}
