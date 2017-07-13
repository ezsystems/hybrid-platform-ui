<?php

namespace spec\EzSystems\HybridPlatformUi\Notification;

use EzSystems\HybridPlatformUi\Notification\Notification;
use PhpSpec\ObjectBehavior;

class NotificationSpec extends ObjectBehavior
{
    private $type = Notification::TYPE_SUCCESS;

    private $timeout = Notification::DEFAULT_TIMEOUT;

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
