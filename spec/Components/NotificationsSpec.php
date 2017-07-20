<?php

namespace spec\EzSystems\HybridPlatformUi\Components;

use EzSystems\HybridPlatformUi\Components\Notifications;
use EzSystems\HybridPlatformUi\Components\Component;
use EzSystems\HybridPlatformUi\Notification\Notification;
use EzSystems\HybridPlatformUi\Notification\NotificationPool;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Templating\EngineInterface;

class NotificationsSpec extends ObjectBehavior
{
    function let(EngineInterface $templating, NotificationPool $notificationPool)
    {
        $this->beConstructedWith($templating, $notificationPool);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Notifications::class);
    }

    function it_is_a_component()
    {
        $this->shouldHaveType(Component::class);
    }

    function it_has_an_exception_property()
    {
        $this->exception = new Notification();
    }

    function it_renders_notifications_from_the_pool_to_a_string(
        EngineInterface $templating,
        NotificationPool $notificationPool
    ) {
        $notification = new Notification([
            'message' => 'the message',
            'timeout' => 0,
            'copyable' => true,
            'details' => 'details',
        ]);
        $notificationPool->get()
            ->shouldBeCalled()
            ->willReturn([$notification]);
        $templating->render(Notifications::NOTIFICATION_TEMPLATE, ['notification' => $notification])
            ->shouldBeCalled()
            ->willReturn('<ez-notification />');
        $this->renderToString()->shouldBe('<ez-notification />');
    }

    function it_renders_notifications_from_the_pool_to_json(
        NotificationPool $notificationPool
    ) {
        $notification = new Notification(['message' => 'the message']);

        $notificationPool->get()
            ->shouldBeCalled()
            ->willReturn([$notification]);
        $this->jsonSerialize()->shouldBe([[
            'type' => null,
            'content' => 'the message',
            'timeout' => 10,
            'details' => null,
            'copyable' => null,
        ]]);
    }

    function it_renders_the_exception_to_json_if_there_is_one(
        EngineInterface $templating,
        NotificationPool $notificationPool
    ) {
        $notificationPool->get()->shouldNotBeCalled();
        $this->exception = new Notification(['type' => 'error']);

        $this->jsonSerialize()->shouldBeArrayOfNotifications([$this->exception]);
    }

    function getMatchers()
    {
        return [
            'beArrayOfNotifications' => function ($array, $notifications): bool {
                foreach ($notifications as $index => $notification) {
                    if (!isset($array[$index])) {
                        return false;
                    }

                    $testedNotification = $array[$index];
                    if (!array_key_exists('type', $testedNotification) || $testedNotification['type'] != $notification->type) {
                        return false;
                    }
                    if (!array_key_exists('copyable', $testedNotification) || $testedNotification['copyable'] != $notification->copyable) {
                        return false;
                    }
                    if (!array_key_exists('content', $testedNotification) || $testedNotification['content'] != $notification->message) {
                        return false;
                    }
                    if (!array_key_exists('details', $testedNotification) || $testedNotification['details'] != $notification->details) {
                        return false;
                    }
                    if (!array_key_exists('timeout', $testedNotification) || $testedNotification['timeout'] != $notification->timeout) {
                        return false;
                    }

                    return true;
                }
            },
        ];
    }
}
