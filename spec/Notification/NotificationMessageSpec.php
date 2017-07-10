<?php

namespace spec\EzSystems\HybridPlatformUi\Notification;

use EzSystems\HybridPlatformUi\Notification\NotificationMessage;
use PhpSpec\ObjectBehavior;

class NotificationMessageSpec extends ObjectBehavior
{
    private $type = NotificationMessage::TYPE_SUCCESS;

    private $timeout = NotificationMessage::DEFAULT_TIMEOUT;

    private $message = 'test message';

    function let()
    {
        $this->beConstructedWith([
            'type' => $this->type,
            'timeout' => $this->timeout,
            'message' => $this->message,
        ]);
    }

    function it_can_be_converted_to_a_string()
    {
        $this->__toString()->shouldBe(
            '<ez-notification type="' . $this->type . '" timeout="' . $this->timeout . '"><p>' . $this->message . '</p></ez-notification>'
        );
    }
}
