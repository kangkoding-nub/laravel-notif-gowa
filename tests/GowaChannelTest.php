<?php

namespace NotificationChannels\Gowa\Test;

use DateTime;
use Illuminate\Notifications\Notification;
use Mockery as M;
use NotificationChannels\Gowa\Exceptions\CouldNotSendNotification;
use NotificationChannels\Gowa\GowaApi;
use NotificationChannels\Gowa\GowaChannel;
use NotificationChannels\Gowa\GowaMessage;
use PHPUnit\Framework\TestCase;

class GowaChannelTest extends TestCase
{
    private GowaApi $gowa;
    private GowaMessage $message;
    private GowaChannel $channel;
    public static DateTime $sendAt;

    public function setUp(): void
    {
        parent::setUp();

        $config = [
            'apiUrl'        => 'https://gowa.own-server.web.id',
            'username'     => 'marifmuntaha',
            'password'     => 'myu2nnmd',
        ];

        $this->gowa = M::mock(GowaApi::class, $config);
        $this->channel = new GowaChannel($this->gowa);
        $this->message = M::mock(GowaMessage::class);
    }

    public function tearDown(): void
    {
        M::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $this->gowa->shouldReceive('send')->once()
            ->with(
                [
                    'to'    => '6282229366506',
                    'body'  => 'hello',
                ]
            )->andReturn(true);
        $this->channel->send(new TestNotifiable(), new TestNotification());
    }

    /** @test */
    public function it_can_send_a_deferred_notification()
    {
        self::$sendAt = new DateTime();

        $this->gowa->shouldReceive('send')->once()
            ->with(
                [
                    'to'    => '60123456789',
                    'body'  => 'hello',
                    'time'  => '0'.self::$sendAt->getTimestamp(),
                ]
            )->andReturn(true);

        $this->channel->send(new TestNotifiable(), new TestNotificationWithSendAt());
    }

    /** @test */
    public function it_does_not_send_a_message_when_to_missed()
    {
        $this->expectException(CouldNotSendNotification::class);

        $this->channel->send(
            new TestNotifiableWithoutRouteNotificationForSmscru(), new TestNotification()
        );
    }
}

class TestNotifiable
{
    public function routeNotificationFor(): string
    {
        return '6202229366506';
    }
}

class TestNotifiableWithoutRouteNotificationForSmscru extends TestNotifiable
{
    public function routeNotificationFor(): string
    {
        return false;
    }
}

class TestNotification extends Notification
{
    public function toGowa()
    {
        return GowaMessage::create('hello');
    }
}

class TestNotificationWithSendAt extends Notification
{
    public function toGowa()
    {
        return GowaMessage::create('hello')
            ->sendAt(GowaChannelTest::$sendAt);
    }
}
