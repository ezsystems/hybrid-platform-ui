<?php

namespace spec\EzSystems\HybridPlatformUi\Http\Session\Flash;

use EzSystems\HybridPlatformUi\Http\Session\Flash\NotificationBag;
use EzSystems\HybridPlatformUi\Notification\Notification;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

class NotificationBagSpec extends ObjectBehavior
{
    function let(Session $session, FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $session->getFlashBag()->willReturn($flashBag);
        $this->beConstructedWith($session, $translator);
    }

    function it_adds_success_notifications(FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $domain = 'domain';
        $parameters = ['%id%' => '1'];

        $flashBag->add(NotificationBag::FLASH_MESSAGE_TYPE, Argument::type(Notification::class))->shouldBeCalled();
        $translator->trans('test', $parameters, $domain)->shouldBeCalled()->willReturn('translated test');

        $this->addSuccess('test', $parameters, $domain);
    }

    function it_adds_error_notifications(FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $domain = 'domain';
        $parameters = ['%id%' => '1'];

        $flashBag->add(NotificationBag::FLASH_MESSAGE_TYPE, Argument::type(Notification::class))->shouldBeCalled();

        $this->addError('test', $parameters, $domain, 'details');
    }

    function it_gets_notifications(FlashBagInterface $flashBag, Notification $notificationMessage)
    {
        $flashBag->get(NotificationBag::FLASH_MESSAGE_TYPE)->willReturn([$notificationMessage]);
        $this->get()->shouldBe([$notificationMessage]);
    }

    function it_peeks_notifications(FlashBagInterface $flashBag, Notification $notificationMessage)
    {
        $flashBag->peek(NotificationBag::FLASH_MESSAGE_TYPE)->willReturn([$notificationMessage]);
        $this->peek()->shouldBe([$notificationMessage]);
    }
}
