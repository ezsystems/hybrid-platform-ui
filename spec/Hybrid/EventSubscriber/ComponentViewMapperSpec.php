<?php

namespace spec\EzSystems\PlatformUIBundle\Hybrid\EventSubscriber;

use eZ\Publish\Core\MVC\Symfony\View\View;
use EzSystems\PlatformUIBundle\Components;
use EzSystems\PlatformUIBundle\Hybrid\EventSubscriber\ComponentViewMapper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig_Environment;

/**
 * @method array getSubscribedEvents()
 * @method mapViewToComponent(GetResponseForControllerResultEvent $event)
 */
class ComponentViewMapperSpec extends ObjectBehavior
{
    function let(
        GetResponseForControllerResultEvent $event,
        Request $request,
        Twig_Environment $twig
    ) {
        $this->beConstructedWith($twig);
        $event->getRequest()->willReturn($request);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ComponentViewMapper::class);
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
        View $view
    ) {
        $this->mapViewToComponent($event);
    }

    function it_does_not_map_non_admin_ui_requests(
        GetResponseForControllerResultEvent $event,
        Request $request
    ) {
        $request->getRequestUri()->willReturn('/foo/bar');
        $event->getControllerResult()->shouldNotBeCalled();

        $this->mapViewToComponent($event);
    }

    function it_maps_controller_results_that_are_views_to_the_MainContent_component(
        GetResponseForControllerResultEvent $event,
        Request $request,
        View $view
    ) {
        $request->getRequestUri()->willReturn('/admin/foo');
        $event->getControllerResult()->willReturn($view);

        $event->setControllerResult(Argument::type(Components\MainContent::class))->shouldBeCalled();

        $this->mapViewToComponent($event);
    }

    public function getMatchers()
    {
        return [
            'subscribeToEvent' => function (array $subscribedEvents, $event) {
                return isset($subscribedEvents[$event])
                    && is_array($subscribedEvents[$event])
                    && count($subscribedEvents[$event]) === 1;
            },
            'havePriorityHigherThan' => function (array $subscribedEvents, $priority) {
                $event = $subscribedEvents[KernelEvents::VIEW][0];
                return isset($event[1]) && $event[1] > $priority;
            }
        ];
    }
}
