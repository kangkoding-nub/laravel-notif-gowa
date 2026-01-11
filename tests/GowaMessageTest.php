<?php

namespace NotificationChannels\Gowa\Test;

use NotificationChannels\Gowa\GowaMessage;
use PHPUnit\Framework\TestCase;

class GowaMessageTest extends TestCase
{
    /** @test */
    public function it_can_accept_a_content_when_constructing_a_message()
    {
        $message = new GowaMessage('hello');

        $this->assertEquals('hello', $message->content);
    }

    /** @test */
    public function it_can_accept_a_content_when_creating_a_message()
    {
        $message = GowaMessage::create('hello');

        $this->assertEquals('hello', $message->content);
    }

    /** @test */
    public function it_can_set_the_content()
    {
        $message = (new GowaMessage())->content('hello');

        $this->assertEquals('hello', $message->content);
    }

    /** @test */
    public function it_can_set_the_send_at()
    {
        $sendAt = date_create();
        $message = (new GowaMessage())->sendAt($sendAt);

        $this->assertEquals($sendAt, $message->sendAt);
    }
}
