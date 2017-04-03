<?php

namespace spec\EzSystems\PlatformUIBundle\Hybrid\EventSubscriber;

use EzSystems\PlatformUIBundle\Components\Component;
use EzSystems\PlatformUIBundle\Hybrid\EventSubscriber\ComponentRendererSubscriber;
use PhpParser\Node\Arg;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ComponentRendererSpec extends ObjectBehavior
{
    function let(
        Component $component,
        GetResponseForControllerResultEvent $event,
        Request $request,
        RequestMatcherInterface $ajaxUpdateRequestMatcher
    ) {
        $this->beConstructedWith($ajaxUpdateRequestMatcher);
        $event->getRequest()->willReturn($request);
        $event->getControllerResult()->willReturn($component);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ComponentRendererSubscriber::class);
        $this->shouldHaveType(EventSubscriberInterface::class);
    }

    function it_subscribes_to_the_view_KernelEvent()
    {
        $this->getSubscribedEvents()->shouldBeArray();
        $this->getSubscribedEvents()->shouldSubscribeToEvent(KernelEvents::VIEW);
    }

    function it_ignores_requests_without_a_component_controller_result(
        GetResponseForControllerResultEvent $event
    ) {
        $event->getControllerResult()->willReturn(false);
        $event->setControllerResult(Argument::any())->shouldNotBeCalled();
        $this->renderComponent($event);
    }

    function it_renders_to_a_JsonResponse_if_the_request_is_an_ajax_update(
        Component $component,
        GetResponseForControllerResultEvent $event,
        RequestMatcherInterface $ajaxUpdateRequestMatcher
    ) {
        $ajaxUpdateRequestMatcher->matches(Argument::any())->willReturn(true);
        $component->jsonSerialize()->willReturn(['jsonSerialized', 'array']);
        $event->setResponse(Argument::type(JsonResponse::class), Argument::cetera())->shouldBeCalled();

        $this->renderComponent($event);
    }

    function it_renders_to_a_standard_Response_if_the_request_is_not_an_ajax_update(
        GetResponseForControllerResultEvent $event,
        Component $component,
        RequestMatcherInterface $ajaxUpdateRequestMatcher
    ) {
        $ajaxUpdateRequestMatcher->matches(Argument::any())->willReturn(false);
        $component->__toString()->willReturn('The component HTML rendering');
        $event->setResponse(Argument::type(Response::class), Argument::cetera())->shouldBeCalled();

        $this->renderComponent($event);
    }

    public function getMatchers()
    {
        return [
            'subscribeToEvent' => function (array $subscribedEvents, $event) {
                return isset($subscribedEvents[$event])
                    && is_array($subscribedEvents[$event])
                    && count($subscribedEvents[$event]) === 1;
            },
        ];
    }
}
