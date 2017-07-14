<?php

namespace spec\EzSystems\HybridPlatformUi\Notification;

use EzSystems\HybridPlatformUi\Notification\Notification;
use EzSystems\HybridPlatformUi\Notification\SessionNotificationPool;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionNotificationPoolSpec extends ObjectBehavior
{
    function let(Session $session, FlashBagInterface $flashBag)
    {
        $session->getFlashBag()->willReturn($flashBag);
        $this->beConstructedWith($session);
    }

    function it_adds_notifications(FlashBagInterface $flashBag, Notification $notification)
    {
        $flashBag->add(SessionNotificationPool::TYPE, $notification)->shouldBeCalled();

        $this->add($notification);
    }

    function it_gets_notifications(FlashBagInterface $flashBag, Notification $notification)
    {
        $flashBag->get(SessionNotificationPool::TYPE)->willReturn([$notification]);

        $this->get()->shouldBe([$notification]);
    }

    function it_peeks_notifications(FlashBagInterface $flashBag, Notification $notification)
    {
        $flashBag->peek(SessionNotificationPool::TYPE)->willReturn([$notification]);

        $this->peek()->shouldBe([$notification]);
    }
}
