<?php

namespace NotificationChannels\Gowa;

use DateTimeInterface;

class GowaMessage
{
    /**
     * The message content.
     *
     * @var string
     */
    public string $content;

    /**
     * Time of sending a message.
     *
     * @var DateTimeInterface|null
     */
    public ?DateTimeInterface $sendAt = null;

    /**
     * Create a new message instance.
     *
     * @param string $content
     *
     * @return static
     */
    public static function create(string $content = ''): static
    {
        return new static($content);
    }

    /**
     * GowaMessage constructor.
     *
     * @param string $content
     */
    public function __construct(string $content = '')
    {
        $this->content = $content;
        $this->sendAt = null;
    }

    /**
     * Set the message content.
     *
     * @param string $content
     *
     * @return $this
     */
    public function content(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the time the message should be sent.
     *
     * @param DateTimeInterface|null $sendAt
     *
     * @return $this
     */
    public function sendAt(?DateTimeInterface $sendAt = null): static
    {
        $this->sendAt = $sendAt;

        return $this;
    }
}
