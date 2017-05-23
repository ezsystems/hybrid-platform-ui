<?php

namespace spec\EzSystems\HybridPlatformUi\EventSubscriber;

use eZ\Publish\Core\MVC\Symfony\View\View;
use EzSystems\HybridPlatformUi\Components;
use EzSystems\HybridPlatformUi\EventSubscriber\CoreViewSubscriber;
use EzSystems\HybridPlatformUi\Mapper\MainContentMapper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use stdClass;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method array getSubscribedEvents()
 * @method mapViewToComponent(GetResponseForControllerResultEvent $event)
 */
class CoreViewSubscriberSpec extends ObjectBehavior
{
    function let(
        GetResponseForControllerResultEvent $event,
        MainContentMapper $mapper,
        Request $request,
        RequestMatcherInterface $adminRequestMatcher,
        View $view
    ) {
        $this->beConstructedWith($mapper, $adminRequestMatcher);
        $event->getRequest()->willReturn($request);
        $event->getControllerResult()->willReturn($view);
        $adminRequestMatcher->matches($request)->willReturn(true);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CoreViewSubscriber::class);
        $this->shouldHaveType(EventSubscriberInterface::class);
    }

    function it_subscribes_to_the_view_KernelEvent()
    {
        $this->getSubscribedEvents()->shouldBeArray();
        $this->getSubscribedEvents()->shouldSubscribeToEvent(KernelEvents::VIEW);
    }

    function it_has_a_higher_priority_than_the_MVC_view_renderer()
    {
        $this->getSubscribedEvents()->shouldHavePriorityHigherThan(0);
    }

    function it_does_not_map_requests_without_a_view_as_controller_result(
        GetResponseForControllerResultEvent $event,
        MainContentMapper $mapper,
        Request $request
    ) {
        $event->getControllerResult()->willReturn(new stdClass());

        $mapper->map(Argument::any())->shouldNotBeCalled();

        $this->mapAdminViewToMainComponent($event);
    }

    function it_does_not_map_non_admin_ui_requests(
        GetResponseForControllerResultEvent $event,
        Request $request,
        RequestMatcherInterface $adminRequestMatcher
    ) {
        $adminRequestMatcher->matches($request)->willReturn(false);

        $event->getControllerResult()->shouldNotBeCalled();

        $this->mapAdminViewToMainComponent($event);
    }

    function it_maps_controller_results_that_are_views_to_the_MainContent_component(
        GetResponseForControllerResultEvent $event,
        Components\MainContent $mainContent,
        MainContentMapper $mapper,
        View $view
    ) {
        $event->getControllerResult()->willReturn($view);

        $mapper->map($view)->shouldBeCalled();
        $event->setResponse(Argument::type(Response::class))->shouldBeCalled();

        $this->mapAdminViewToMainComponent($event);
    }

    public function getMatchers()
    {
        return [
            'subscribeToEvent' => function (array $subscribedEvents, $event) {
                return isset($subscribedEvents[$event])
                    && is_array($subscribedEvents[$event])
                    && count($subscribedEvents[$event]) === 2;
            },
            'havePriorityHigherThan' => function (array $subscribedEvents, $priority) {
                return isset($subscribedEvents[KernelEvents::VIEW][1])
                    && $subscribedEvents[KernelEvents::VIEW][1] > $priority;
            }
        ];
    }
}
