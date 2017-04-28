<?php

namespace NotificationChannels\ClickSend;

use Illuminate\Notifications\Notification;
use NotificationChannels\ClickSend\Exceptions\CouldNotSendNotification;

class ClickSendChannel
{
    /** @var \NotificationChannels\ClickSend\ClickSendApi */
    protected $api;

    public function __construct(ClickSendApi $api)
    {
        $this->api = $api;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     *
     * @throws  \NotificationChannels\ClickSend\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $to = $notifiable->routeNotificationForClicksend();

        if (empty($to)) {
            throw CouldNotSendNotification::missingRecipient();
        }

        $message = $notification->toClickSend($notifiable);

        if (is_string($message)) {
            $message = new ClickSendMessage($message);
        }

        return $this->api->sendSms($message->from, $to, $message->content);
    }

}
