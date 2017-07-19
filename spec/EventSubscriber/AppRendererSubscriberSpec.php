<?php

namespace spec\EzSystems\HybridPlatformUi\EventSubscriber;

use EzSystems\HybridPlatformUi\App\ToolbarsConfigurator;
use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\EventSubscriber\AppRendererSubscriber;
use EzSystems\HybridPlatformUi\Http\AjaxUpdateRequestMatcher;
use EzSystems\HybridPlatformUi\Http\HybridRequestMatcher;
use EzSystems\HybridPlatformUi\Http\Response\NotificationResponse;
use EzSystems\HybridPlatformUi\Http\Response\ResetResponse;
use PhpParser\Node\Arg;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method array getSubscribedEvents()
 * @method renderApp()
 */
class AppRendererSubscriberSpec extends ObjectBehavior
{
    function let(
        App $app,
        GetResponseForControllerResultEvent $event,
        Request $request,
        Response $response,
        ParameterBag $requestAttributes,
        HybridRequestMatcher $hybridRequestMatcher,
        AjaxUpdateRequestMatcher $ajaxUpdateRequestMatcher,
        ToolbarsConfigurator $toolbarsConfigurator
    ) {
        $hybridRequestMatcher->matches(Argument::type(Request::class))->willReturn(true);
        $request->attributes = $requestAttributes;
        $request->duplicate(Argument::cetera())->willReturn(new Request());

        $event->getRequest()->willReturn($request);
        $event->getResponse()->willReturn($response);
        $event->isMasterRequest()->willReturn(true);
        $response->isRedirect()->willReturn(false);

        $this->beConstructedWith(
            $app,
            $hybridRequestMatcher,
            $ajaxUpdateRequestMatcher,
            $toolbarsConfigurator
        );
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
        FilterResponseEvent $event,
        Request $request,
        HybridRequestMatcher $hybridRequestMatcher
    ) {
        $hybridRequestMatcher->matches($request)->willReturn(false);
        $event->setResponse(Argument::type(Response::class))->shouldNotBeCalled();

        $this->renderApp($event);
    }

    function it_ignores_sub_requests(FilterResponseEvent $event)
    {
        $event->isMasterRequest()->willReturn(false);
        $event->getRequest()->shouldNotBeCalled();

        $this->renderApp($event);
    }

    function it_ignores_redirect_responses(
        FilterResponseEvent $event,
        Response $response
    ) {
        $response->isRedirect()->willReturn(true);
        $event->setResponse(Argument::type(Response::class))->shouldNotBeCalled();

        $this->renderApp($event);
    }

    function it_ignores_reset_responses(
        FilterResponseEvent $event,
        ResetResponse $resetResponse
    ) {
        $event->getResponse()->willReturn($resetResponse);
        $event->setResponse(Argument::type(Response::class))->shouldNotBeCalled();

        $this->renderApp($event);
    }

    function it_ignores_notification_responses(
        FilterResponseEvent $event,
        NotificationResponse $notificationResponse
    ) {
        $event->getResponse()->willReturn($notificationResponse);
        $event->setResponse(Argument::type(Response::class))->shouldNotBeCalled();

        $this->renderApp($event);
    }

    function it_configures_the_toolbars_and_renders_the_app(
        App $app,
        FilterResponseEvent $event,
        ToolbarsConfigurator $toolbarsConfigurator
    ) {
        $toolbarsConfigurator->configureToolbars($app)->shouldBeCalled();
        $event->setResponse(Argument::type(Response::class))->shouldBeCalled();

        $this->renderApp($event);
    }

    function it_renders_ajax_update_requests_to_json(
        AjaxUpdateRequestMatcher $ajaxUpdateRequestMatcher,
        App $app,
        FilterResponseEvent $event,
        Request $request
    ) {
        $ajaxUpdateRequestMatcher->matches($request)->shouldBeCalled()->willReturn(true);
        $app->jsonSerialize()->shouldBeCalled()->willReturn("update json");
        $event->setResponse(Argument::type(JsonResponse::class))->shouldBeCalled();

        $this->renderApp($event);
    }

    function it_renders_hybrid_requests_to_html(
        AjaxUpdateRequestMatcher $ajaxUpdateRequestMatcher,
        App $app,
        FilterResponseEvent $event,
        Request $request
    ) {
        $ajaxUpdateRequestMatcher->matches($request)->shouldBeCalled()->willReturn(false);
        $app->renderToString()->shouldBeCalled()->willReturn("<html />");
        $event->setResponse(Argument::type(Response::class))->shouldBeCalled();

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
        ];
    }
}
