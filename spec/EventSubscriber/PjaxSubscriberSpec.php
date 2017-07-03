<?php

namespace spec\EzSystems\HybridPlatformUi\EventSubscriber;

use EzSystems\HybridPlatformUi\EventSubscriber\PjaxSubscriber;
use EzSystems\HybridPlatformUi\Http\AdminRequestMatcher;
use EzSystems\HybridPlatformUi\Pjax\PjaxResponseMatcher;
use EzSystems\HybridPlatformUi\Pjax\PjaxResponseMainContentMapper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class PjaxSubscriberSpec extends ObjectBehavior
{
    function let(
        FilterResponseEvent $event,
        PjaxResponseMainContentMapper $mapper,
        Request $request,
        AdminRequestMatcher $adminRequestMatcher,
        RequestMatcherInterface $pjaxRequestMatcher,
        PjaxResponseMatcher $pjaxResponseMatcher,
        Response $response
    ) {
        $event->getRequest()->willReturn($request);
        $event->getResponse()->willReturn($response);
        $event->getRequestType()->willReturn(HttpKernelInterface::MASTER_REQUEST);

        $adminRequestMatcher->matches($request)->willReturn(true);
        $pjaxRequestMatcher->matches($request)->willReturn(true);

        $this->beConstructedWith($mapper, $adminRequestMatcher, $pjaxRequestMatcher, $pjaxResponseMatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PjaxSubscriber::class);
        $this->shouldHaveType(EventSubscriberInterface::class);
    }

    function it_ignores_sub_requests(FilterResponseEvent $event)
    {
        $event->getRequestType()->willReturn(HttpKernelInterface::SUB_REQUEST);

        $this->mapPjaxResponseToMainContent($event);
    }

    function it_ignores_non_admin_requests(
        FilterResponseEvent $event,
        Request $request,
        AdminRequestMatcher $adminRequestMatcher
    ) {
        $adminRequestMatcher->matches($request)->willReturn(false);

        $this->mapPjaxResponseToMainContent($event);
    }

    function it_ignores_non_pjax_requests(
        FilterResponseEvent $event,
        Request $request,
        RequestMatcherInterface $pjaxRequestMatcher
    ) {
        $pjaxRequestMatcher->matches($request)->willReturn(false);

        $this->mapPjaxResponseToMainContent($event);
    }

    function it_maps_pjax_redirect_responses_to_redirect_responses_using_the_pjax_location_response_header(
        FilterResponseEvent $event,
        RedirectResponse $redirectResponse
    ) {
        $event->getResponse()->willReturn($redirectResponse);
        $event->setResponse(Argument::any())->shouldNotBeCalled();
        $event->stopPropagation()->shouldBeCalled();

        $this->mapPjaxResponseToMainContent($event);
    }

    function it_sets_the_app_maincontent_result_with_the_value_returned_by_the_mapper(
        FilterResponseEvent $event,
        PjaxResponseMainContentMapper $mapper,
        Response $response
    ) {
        $mapper->map($response)->shouldBeCalled();
        $this->mapPjaxResponseToMainContent($event);
    }
}
