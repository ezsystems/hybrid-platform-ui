<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace spec\EzSystems\HybridPlatformUi\Http\Response;

use EzSystems\HybridPlatformUi\Http\Response\NoRenderResponse;
use EzSystems\HybridPlatformUi\Notification\Notification;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Response;

class NotificationResponseSpec extends ObjectBehavior
{
    private $type = Notification::TYPE_SUCCESS;

    private $timeout = Notification::DEFAULT_TIMEOUT;

    private $content = 'test message';

    function let()
    {
        $notification = new Notification([
            'type' => $this->type,
            'timeout' => $this->timeout,
            'content' => $this->content,
        ]);

        $this->beConstructedWith($notification);
    }

    function it_is_a_response()
    {
        $this->shouldBeAnInstanceOf(Response::class);
    }

    function it_bypasses_app_render()
    {
        $this->shouldBeAnInstanceOf(NoRenderResponse::class);
    }

    function it_converts_notification_to_string()
    {
        $this->getContent()->shouldBe(
            '<ez-notification type="' . $this->type . '" timeout="' . $this->timeout . '">' . $this->content . '</ez-notification>'
        );
    }
}
