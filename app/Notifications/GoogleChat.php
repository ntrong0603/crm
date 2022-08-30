<?php

namespace App\Notifications;

use App\Channels\GoogleChannel;
use Illuminate\Notifications\Notification;

class GoogleChat extends Notification
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $webhook;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, $webhookUrl = null)
    {
        $this->message = $message;
        $this->webhook = $webhookUrl;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [GoogleChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return GoogleMessage
     */
    public function toGoogle($notifiable)
    {
        return [
            'message' => $this->message,
            'webhook' => $this->webhook
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
