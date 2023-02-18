<?php

namespace Illuminate\Notifications\Tests\Feature;


use Illuminate\Notifications\Client\Client;

class ClientBasicAPICredentialsTest extends FeatureTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('labsmobile.username', 'user');
        $app['config']->set('labsmobile.password', '123456');

    }

    public function testClientCreatedWithBasicAPICredentials()
    {
        $credentials = $this->app->make(Client::class)->getCredentials();

        //$this->assertInstanceOf(Basic::class, $credentials);
        $this->assertEquals(['username' => 'user', 'password' => '123456'], $credentials);
    }
}
