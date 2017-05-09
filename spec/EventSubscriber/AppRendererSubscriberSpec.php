<?php

namespace spec\EzSystems\HybridPlatformUi\EventSubscriber;

use EzSystems\HybridPlatformUi\App\AppResponseRenderer;
use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Components\Component;
use EzSystems\HybridPlatformUi\EventSubscriber\AppRendererSubscriber;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use stdClass;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method array getSubscribedEvents()
 * @method renderApp()
 */
class AppRendererSubscriberSpec extends ObjectBehavior
{
    function let(
        App $app,
        AppResponseRenderer $renderer,
        GetResponseForControllerResultEvent $event,
        Request $request,
        Response $response,
        ParameterBag $requestAttributes,
        RequestMatcherInterface $adminRequestMatcher
    ) {
        $adminRequestMatcher->matches(Argument::type(Request::class))->willReturn(true);
        $request->attributes = $requestAttributes;
        $request->duplicate(Argument::cetera())->willReturn(new Request());

        $event->getRequest()->willReturn($request);
        $event->getRequestType()->willReturn(HttpKernelInterface::MASTER_REQUEST);
        $event->getResponse()->willReturn($response);

        $this->beConstructedWith($app, $renderer, $adminRequestMatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AppRendererSubscriber::class);
        $this->shouldHaveType(EventSubscriberInterface::class);
    }

    function it_subscribes_to_the_response_KernelEvent()
    {
        $this->getSubscribedEvents()->shouldBeArray();
        $this->getSubscribedEvents()->shouldSubscribeToEvent(KernelEvents::RESPONSE);
    }

    function it_ignores_non_admin_requests(
        AppResponseRenderer $renderer,
        FilterResponseEvent $event,
        Request $request,
        RequestMatcherInterface $adminRequestMatcher,
        Response $response
    ) {
        $adminRequestMatcher->matches($request)->willReturn(false);
        $renderer->render($response)->shouldNotBeCalled();

        $this->renderApp($event);
    }

    function it_ignores_sub_requests(FilterResponseEvent $event)
    {
        $event->getRequestType()->willReturn(HttpKernelInterface::SUB_REQUEST);
        $event->getRequest()->shouldNotBeCalled();
        $this->renderApp($event);
    }

    function it_renders_the_app(
        App $app,
        AppResponseRenderer $renderer,
        FilterResponseEvent $event
    ) {
        $renderer->render(Argument::type(Response::class), $app)->shouldBeCalled();

        $this->renderApp($event);

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
                $event = $subscribedEvents[KernelEvents::VIEW];
                return isset($event[1]) && $event[1] > $priority;
            },
            'havePriorityLowerThan' => function (array $subscribedEvents, $priority) {
                $event = $subscribedEvents[KernelEvents::VIEW];
                return isset($event[1]) && $event[1] < $priority;
            }
        ];
    }
}
