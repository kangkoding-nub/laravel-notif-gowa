<?php

namespace NotificationChannels\Gowa\Exceptions;

use DomainException;
use Exception;

class CouldNotSendNotification extends Exception
{
    /**
     * Thrown when recipient's phone number is missing.
     *
     * @return static
     */
    public static function missingRecipient(): static
    {
        return new static('Pemberitahuan tidak terkirim. Nomor telepon tidak ada.');
    }

    /**
     * Thrown when content length is greater than 800 characters.
     *
     * @return static
     */
    public static function contentLengthLimitExceeded(): static
    {
        return new static(
            'Pemberitahuan tidak terkirim. Panjang konten tidak boleh lebih dari 800 karakter.'
        );
    }

    /**
     * Thrown when we're unable to communicate.
     *
     * @param  DomainException  $exception
     *
     * @return static
     */
    public static function exceptionGowaRespondedWithAnError(DomainException $exception): static
    {
        return new static(
            "Gowa merespons dengan kesalahan '{$exception->getCode()}: {$exception->getMessage()}'"
        );
    }

    /**
     * Thrown when we're unable to communicate with GOWA.
     *
     * @param  Exception  $exception
     *
     * @return static
     */
    public static function couldNotCommunicateWithGowa(Exception $exception): static
    {
        return new static("Komunikasi dengan GOWA gagal. Alasan: {$exception->getMessage()}");
    }
}
