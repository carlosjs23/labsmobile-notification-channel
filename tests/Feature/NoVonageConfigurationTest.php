<?php

namespace Illuminate\Notifications\Tests\Feature;

use Illuminate\Notifications\Client\Client;
use RuntimeException;

class NoVonageConfigurationTest extends FeatureTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('labsmobile.api_key', 'my_api_key');
    }

    public function testWhenNoConfigurationIsGivenExceptionIsRaised()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Please provide your LabsMobile API credentials.');

        app(Client::class);
    }
}
