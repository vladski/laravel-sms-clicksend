<?php

namespace NotificationChannels\ClickSend\Exceptions;

use Exception;
use DomainException;

class CouldNotSendNotification extends Exception
{
    /**
     * Thrown when recipient's phone number is missing.
     *
     * @return static
     */
    public static function missingRecipient()
    {
        return new static('Notification was not sent. Phone number is missing.');
    }

    /**
     * Thrown when content length is greater than 800 characters.
     *
     * @return static
     */
    public static function contentLengthLimitExceeded()
    {
        return new static(
            'Notification was not sent. Content length may not be greater than 800 characters.'
        );
    }

    /**
     * Thrown when mesage status is not SUCCESS
     *
     * @param  DomainException  $exception
     *
     * @return static
     */
    public static function clicksendRespondedWithAnError(DomainException $exception)
    {
        return new static(
            "Notification Error: {$exception->getMessage()}"
        );
    }

    /**
     * Thrown when we're unable to communicate with Clicksend.com
     *
     * @param  Exception  $exception
     *
     * @return static
     */
    public static function couldNotCommunicateWithClicksend(Exception $exception)
    {
        return new static("Notification Gateway Error: {$exception->getReason()} [{$exception->getCode()}]");
    }
}
