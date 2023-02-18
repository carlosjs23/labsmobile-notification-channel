<?php

namespace Illuminate\Notifications\Messages;

class LabsMobileMessage
{
    /**
     * The message content.
     *
     * @var string
     */
    public $content;

    /**
     * The phone number the message should be sent from.
     *
     * @var string
     */
    public $from;

    /**
     * The phone number the message should be sent to.
     *
     * @var string
     */
    public $to;

    /**
     * The message type.
     *
     * @var string
     */
    public $type = 'text';

    /**
     * The custom LabsMobile client instance.
     *
     * @var \Illuminate\Notifications\Client\Client|null
     */
    public $client;

    /**
     * The client reference.
     *
     * @var string
     */
    public $clientReference = '';


    /**
     * Create a new message instance.
     *
     * @param  string  $content
     * @return void
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * Set the message content.
     *
     * @param  string  $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the phone number the message should be sent from.
     *
     * @param  string  $from
     * @return $this
     */
    public function from($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Set the phone number the message should be sent from.
     *
     * @param  string  $to
     * @return $this
     */
    public function to($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Set the message type.
     *
     * @return $this
     */
    public function unicode()
    {
        $this->type = 'unicode';

        return $this;
    }

    /**
     * Set the client reference (up to 255 characters).
     *
     * @param  string  $clientReference
     * @return $this
     */
    public function clientReference($clientReference)
    {
        $this->clientReference = $clientReference;

        return $this;
    }

    /**
     * Set the LabsMobile client instance.
     *
     * @param  \Illuminate\Notifications\Client\Client  $client
     * @return $this
     */
    public function usingClient($client)
    {
        $this->client = $client;

        return $this;
    }
}
