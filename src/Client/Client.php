<?php

namespace Illuminate\Notifications\Client;

use Composer\InstalledVersions;
use GuzzleHttp\ClientInterface;
use Illuminate\Notifications\Messages\LabsMobileMessage;

class Client {
    use MakesRequests;

    /**
     * The Guzzle HTTP Client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * The API's URL.
     *
     * @var string
     */
    protected $apiBase = 'https://api.labsmobile.com/get/send.php';

    protected $credentials;

    public function __construct($credentials, ?ClientInterface $client = null)
    {
        if (is_null($client)) {
            // Since the user did not pass a client, try and make a client
            // using the Guzzle 6 adapter or Guzzle 7 (depending on availability)
            list($guzzleVersion) = explode('@', InstalledVersions::getVersion('guzzlehttp/guzzle'), 1);
            $guzzleVersion = (float) $guzzleVersion;

            if ($guzzleVersion >= 6.0 && $guzzleVersion < 7) {
                /** @noinspection CallableParameterUseCaseInTypeContextInspection */
                /** @noinspection PhpUndefinedNamespaceInspection */
                /** @noinspection PhpUndefinedClassInspection */
                $client = new \Http\Adapter\Guzzle6\Client();
            }

            if ($guzzleVersion >= 7.0 && $guzzleVersion < 8.0) {
                $client = new \GuzzleHttp\Client();
            }
        }
        $this->setHttpClient($client);
        $this->credentials = $credentials;
        $this->buildBody($this->credentials);
    }

    /**
     * Set the Http Client to used to make API requests.
     *
     * This allows the default http client to be swapped out for a HTTPlug compatible
     * replacement.
     */
    public function setHttpClient(ClientInterface $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get the Http Client used to make API requests.
     */
    public function getHttpClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * Get the user credentials used to make API requests.
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * Sends a SMS message.
     *
     * @param LabsMobileMessage $message The SMS message instance.
     */
    public function send(LabsMobileMessage $message)
    {
        $data = [
            'msisdn'  => $message->to,
            'message' => $message->content,
        ];

        $this->buildBody($data);

        $this->getRequest();

        return $this;
    }
}
