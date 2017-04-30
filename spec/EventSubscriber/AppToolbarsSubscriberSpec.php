<?php

namespace spec\EzSystems\HybridPlatformUi\EventSubscriber;

use EzSystems\HybridPlatformUi\EventSubscriber\AppToolbarsSubscriber;
use EzSystems\HybridPlatformUi\Toolbars\ToolbarsConfigurator;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AppToolbarsSubscriberSpec extends ObjectBehavior
{
    function let(
        GetResponseEvent $event,
        Request $request,
        RequestMatcherInterface $adminRequestMatcher,
        ToolbarsConfigurator $configurator
    ) {
        $this->beConstructedWith($adminRequestMatcher, $configurator);
        $event->getRequest()->willReturn($request);
        $adminRequestMatcher->matches($request)->willReturn(true);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AppToolbarsSubscriber::class);
        $this->shouldHaveType(EventSubscriberInterface::class);
    }

    function it_subscribes_to_the_request_kernel_event()
    {
        $this->getSubscribedEvents()->shouldSubscribeToEvent(KernelEvents::REQUEST);
    }

    function it_ignores_non_admin_requests(
        GetResponseEvent $event,
        Request $request,
        RequestMatcherInterface $adminRequestMatcher,
        ToolbarsConfigurator $configurator
    ) {
        $adminRequestMatcher->matches($request)->willReturn(false);
        $configurator->fromRequest($request)->shouldNotBeCalled();

        $this->configureAppToolbars($event);
    }

    function it_configures_the_toolbars(
        GetResponseEvent $event,
        Request $request,
        ToolbarsConfigurator $configurator
    ) {
        $configurator->fromRequest($request)->shouldBeCalled();

        $this->configureAppToolbars($event);
    }

    public function getMatchers()
    {
        return [
            'subscribeToEvent' => function (array $subscribedEvents, $event) {
                return isset($subscribedEvents[$event])
                    && is_array($subscribedEvents[$event])
                    && count($subscribedEvents[$event]) === 2 || count($subscribedEvents[$event]) === 1;
            },
        ];
    }
}
