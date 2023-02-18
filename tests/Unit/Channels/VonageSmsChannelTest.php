<?php

namespace Illuminate\Notifications\Tests\Unit\Channels;

use Hamcrest\Core\IsEqual;
use Illuminate\Notifications\Channels\LabsMobileSmsChannel;
use Illuminate\Notifications\Client\Client;
use Illuminate\Notifications\Messages\LabsMobileMessage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Vonage\SMS\Message\SMS;

class VonageSmsChannelTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testSmsIsSentViaVonage()
    {
        $notification = new NotificationVonageSmsChannelTestNotification;
        $notifiable = new NotificationVonageSmsChannelTestNotifiable;

        $channel = new LabsMobileSmsChannel(
            $vonage = m::mock(Client::class), '4444444444'
        );

        $mockSms = (new LabsMobileMessage('this is my message'))
            ->from('4444444444')
            ->to('5555555555');

        $vonage->shouldReceive('send')
            ->with(IsEqual::equalTo($mockSms))
            ->once();

        $channel->send($notifiable, $notification);
    }

    public function testSmsWillSendAsUnicode()
    {
        $notification = new NotificationVonageUnicodeSmsChannelTestNotification;
        $notifiable = new NotificationVonageSmsChannelTestNotifiable;

        $channel = new LabsMobileSmsChannel(
            $vonage = m::mock(Client::class), '4444444444'
        );

        $mockSms = (new LabsMobileMessage('this is my message'))
            ->from('4444444444')
            ->to('5555555555')
            ->unicode();

        $vonage->shouldReceive('send')
               ->with(IsEqual::equalTo($mockSms))
               ->once();

        $channel->send($notifiable, $notification);
    }

    public function testSmsIsSentViaVonageWithCustomClient()
    {
        $customVonage = m::mock(Client::class);
        $customVonage->shouldReceive('send')
            ->with(IsEqual::equalTo((new LabsMobileMessage('this is my message'))
                ->from('4444444444')
                ->to('5555555555')
            ))->once();

        $notification = new NotificationVonageSmsChannelTestCustomClientNotification($customVonage);
        $notifiable = new NotificationVonageSmsChannelTestNotifiable;

        $channel = new LabsMobileSmsChannel(
            $vonage = m::mock(Client::class), '4444444444'
        );

       $vonage->shouldNotReceive('send');

        $channel->send($notifiable, $notification);
    }

    public function testSmsIsSentViaVonageWithCustomFrom()
    {
        $notification = new NotificationVonageSmsChannelTestCustomFromNotification;
        $notifiable = new NotificationVonageSmsChannelTestNotifiable;

        $channel = new LabsMobileSmsChannel(
            $vonage = m::mock(Client::class), '4444444444'
        );
        $mockSms = (new LabsMobileMessage('this is my message'))
            ->from('5554443333')
            ->to('5555555555');

        $vonage->shouldReceive('send')
            ->with(IsEqual::equalTo($mockSms))
            ->once();

        $channel->send($notifiable, $notification);
    }

    public function testSmsIsSentViaVonageWithCustomFromAndClient()
    {
        $customVonage = m::mock(Client::class);

        $mockSms = (new LabsMobileMessage('this is my message'))
            ->from('5554443333')
            ->to('5555555555');

        $customVonage->shouldReceive('send')
            ->with(IsEqual::equalTo($mockSms))
            ->once();

        $notification = new NotificationVonageSmsChannelTestCustomFromAndClientNotification($customVonage);
        $notifiable = new NotificationVonageSmsChannelTestNotifiable;

        $channel = new LabsMobileSmsChannel(
            $vonage = m::mock(Client::class), '4444444444'
        );

        $vonage->shouldNotReceive('send');

        $channel->send($notifiable, $notification);
    }

    public function testSmsIsSentViaVonageWithCustomFromAndClientRef()
    {
        $notification = new NotificationVonageSmsChannelTestCustomFromAndClientRefNotification;
        $notifiable = new NotificationVonageSmsChannelTestNotifiable;

        $channel = new LabsMobileSmsChannel(
            $vonage = m::mock(Client::class), '4444444444'
        );

        $mockSms = (new LabsMobileMessage('this is my message'))
            ->from('5554443333')
            ->to('5555555555');

        $mockSms->clientReference('11');

        $vonage->shouldReceive('send')
            ->with(IsEqual::equalTo($mockSms))
            ->once();

        $channel->send($notifiable, $notification);
    }

    public function testSmsIsSentViaVonageWithCustomClientFromAndClientRef()
    {
        $customVonage = m::mock(Client::class);

        $mockSms = (new LabsMobileMessage('this is my message'))
            ->from('5554443333')
            ->to('5555555555');

        $mockSms->clientReference('11');

        $customVonage->shouldReceive('send')
            ->with(IsEqual::equalTo($mockSms))
            ->once();

        $notification = new NotificationVonageSmsChannelTestCustomClientFromAndClientRefNotification($customVonage);
        $notifiable = new NotificationVonageSmsChannelTestNotifiable;

        $channel = new LabsMobileSmsChannel(
            $vonage = m::mock(Client::class), '4444444444'
        );

        $vonage->shouldNotReceive('sms->send');

        $channel->send($notifiable, $notification);
    }
}

class NotificationVonageSmsChannelTestNotifiable
{
    use Notifiable;

    public $phone_number = '5555555555';

    public function routeNotificationForLabsMobile($notification)
    {
        return $this->phone_number;
    }
}

class NotificationVonageSmsChannelTestNotification extends Notification
{
    public function toLabsMobile($notifiable)
    {
        return new LabsMobileMessage('this is my message');
    }
}

class NotificationVonageUnicodeSmsChannelTestNotification extends Notification
{
    public function toLabsMobile($notifiable)
    {
        return (new LabsMobileMessage('this is my message'))->unicode();
    }
}

class NotificationVonageSmsChannelTestCustomClientNotification extends Notification
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function toLabsMobile($notifiable)
    {
        return (new LabsMobileMessage('this is my message'))->usingClient($this->client);
    }
}

class NotificationVonageSmsChannelTestCustomFromNotification extends Notification
{
    public function toLabsMobile($notifiable)
    {
        return (new LabsMobileMessage('this is my message'))->from('5554443333');
    }
}

class NotificationVonageSmsChannelTestCustomFromAndClientNotification extends Notification
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function toLabsMobile($notifiable)
    {
        return (new LabsMobileMessage('this is my message'))->from('5554443333')->usingClient($this->client);
    }
}

class NotificationVonageSmsChannelTestCustomFromAndClientRefNotification extends Notification
{
    public function toLabsMobile($notifiable)
    {
        return (new LabsMobileMessage('this is my message'))->from('5554443333')->clientReference('11');
    }
}

class NotificationVonageSmsChannelTestCustomClientFromAndClientRefNotification extends Notification
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function toLabsMobile($notifiable)
    {
        return (new LabsMobileMessage('this is my message'))
            ->from('5554443333')
            ->clientReference('11')
            ->usingClient($this->client);
    }
}
