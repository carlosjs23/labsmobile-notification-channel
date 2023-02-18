<?php

namespace Illuminate\Notifications\Channels;

use Illuminate\Notifications\Client\Client;
use Illuminate\Notifications\Messages\LabsMobileMessage;
use Illuminate\Notifications\Notification;

class LabsMobileSmsChannel
{
    /**
     * The LabsMobile client instance.
     *
     * @var \Illuminate\Notifications\Client\Client
     */
    protected $client;

    /**
     * The phone number notifications should be sent from.
     *
     * @var string
     */
    protected $from;

    /**
     * Create a new LabsMobile channel instance.
     *
     * @param Client $client
     * @param string $from
     */
    public function __construct(Client $client, $from)
    {
        $this->from = $from;
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return \Vonage\SMS\Collection|null
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('labsmobile', $notification)) {
            return;
        }

        $message = $notification->toLabsMobile($notifiable);

        if (is_string($message)) {
            $message = new LabsMobileMessage($message);
        }

        $labsMobileSms = new LabsMobileMessage(trim($message->content));
        $labsMobileSms->to($to);
        $labsMobileSms->clientReference($message->clientReference);
        $labsMobileSms->from($message->from ?: $this->from);

        if ($message->type != 'text') {
            $labsMobileSms->unicode();
        }

        return ($message->client ?? $this->client)->send($labsMobileSms);
    }
}
