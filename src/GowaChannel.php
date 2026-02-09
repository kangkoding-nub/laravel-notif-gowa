<?php

namespace NotificationChannels\Gowa;

use DateTimeInterface;
use Illuminate\Notifications\Notification;
use NotificationChannels\Gowa\Exceptions\CouldNotSendNotification;

class GowaChannel
{
    /** @var GowaApi */
    protected GowaApi $gowa;

    public function __construct(GowaApi $gowa)
    {
        $this->gowa = $gowa;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param Notification $notification
     *
     * @throws  CouldNotSendNotification
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $to = $notifiable->routeNotificationFor('gowa');

        if (empty($to)) {
            throw CouldNotSendNotification::missingRecipient();
        }

        $message = $notification->toGowa($notifiable);

        if (is_string($message)) {
            $message = new GowaMessage($message);
        }

        $this->sendMessage($to, $message);
    }

    protected function sendMessage(string $recipient, GowaMessage $message): void
    {
        $params = [
            'to'   => preg_replace('/[^0-9]/', '', $recipient),
            'body' => $message->content,
        ];

        if ($message->sendAt instanceof DateTimeInterface) {
            $params['time'] = $message->sendAt->getTimestamp();
        }

        $this->gowa->send($params);
    }
}
