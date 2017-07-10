<?php

namespace spec\EzSystems\HybridPlatformUi\Builder;

use EzSystems\HybridPlatformUi\Notification\NotificationMessage;
use PhpSpec\ObjectBehavior;

class NotificationBuilderSpec extends ObjectBehavior
{
    function it_build_success_notification()
    {
        $message = 'test';

        $expectedNotification = new NotificationMessage(
            [
                'type' => NotificationMessage::TYPE_SUCCESS,
                'message' => $message,
                'timeout' => NotificationMessage::DEFAULT_TIMEOUT,
                'copyable' => null,
                'details' => null,
            ]
        );

        $this
            ->setSuccess()
            ->setMessage($message)
            ->getResult()
            ->shouldBeLike($expectedNotification);
    }

    function it_build_error_notification()
    {
        $message = 'test';
        $details = 'test details';

        $expectedNotification = new NotificationMessage(
            [
                'type' => NotificationMessage::TYPE_ERROR,
                'message' => $message,
                'timeout' => NotificationMessage::DEFAULT_TIMEOUT,
                'copyable' => true,
                'details' => $details,
            ]
        );

        $this
            ->setError()
            ->setErrorDetails($details)
            ->setMessage($message)
            ->getResult()
            ->shouldBeLike($expectedNotification);
    }
}
