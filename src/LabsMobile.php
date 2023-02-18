<?php

namespace Illuminate\Notifications;

use Illuminate\Notifications\Client\Client;
use Psr\Http\Client\ClientInterface;
use RuntimeException;


class LabsMobile
{
    /**
     * The LabsMobile configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * The HttpClient instance, if provided.
     *
     * @var \Psr\Http\Client\ClientInterface
     */
    protected $client;

    /**
     * Create a new LabsMobile instance.
     *
     * @param  array  $config
     * @param  \Psr\Http\Client\ClientInterface|null  $client
     * @return void
     */
    public function __construct(array $config = [], ?ClientInterface $client = null)
    {
        $this->config = $config;
        $this->client = $client;
    }

    /**
     * Create a new LabsMobile instance.
     *
     * @param  array  $config
     * @param  \Psr\Http\Client\ClientInterface|null  $client
     * @return static
     */
    public static function make(array $config, ?ClientInterface $client = null)
    {
        return new static($config, $client);
    }

    /**
     * Create a new LabsMobile Client.
     *
     * @return Client
     *
     * @throws \RuntimeException
     */
    public function client()
    {
        $basicCredentials = null;

        if ($username = $this->config['username'] ?? null) {
            $basicCredentials['username'] = $username;
        }

        if ($password = $this->config['password'] ?? null) {
            $basicCredentials['password'] = $password;
        }

        if ($basicCredentials['username'] ?? null && $basicCredentials['password'] ?? null) {
            $credentials = $basicCredentials;
        } else {
            throw new RuntimeException(
                'Please provide your LabsMobile API credentials.'
            );
        }

        return new Client($credentials, $this->client);
    }
}
