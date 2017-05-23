<?php

namespace spec\EzSystems\HybridPlatformUi\EventSubscriber;

use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Components\MainContent;
use EzSystems\HybridPlatformUi\EventSubscriber\PjaxSubscriber;
use EzSystems\HybridPlatformUi\Mapper\MainContentMapper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class PjaxSubscriberSpec extends ObjectBehavior
{
    function let(
        FilterResponseEvent $event,
        MainContentMapper $mapper,
        Request $request,
        RequestMatcherInterface $adminRequestMatcher,
        RequestMatcherInterface $pjaxRequestMatcher,
        Response $response
    ) {
        $this->beConstructedWith($mapper, $adminRequestMatcher, $pjaxRequestMatcher);

        $event->getRequest()->willReturn($request);
        $event->getResponse()->willReturn($response);
        $adminRequestMatcher->matches($request)->willReturn(true);
        $pjaxRequestMatcher->matches($request)->willReturn(true);
        $event->getRequestType()->willReturn(HttpKernelInterface::MASTER_REQUEST);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PjaxSubscriber::class);
        $this->shouldHaveType(EventSubscriberInterface::class);
    }

    function it_ignores_sub_requests(FilterResponseEvent $event)
    {
        $event->getRequestType()->willReturn(HttpKernelInterface::SUB_REQUEST);
        $event->getResponse()->shouldNotBeCalled();

        $this->mapPjaxResponseToMainContent($event);
    }

    function it_ignores_non_admin_requests(
        FilterResponseEvent $event,
        Request $request,
        RequestMatcherInterface $adminRequestMatcher
    ) {
        $adminRequestMatcher->matches($request)->willReturn(false);
        $event->getResponse()->shouldNotBeCalled();

        $this->mapPjaxResponseToMainContent($event);
    }

    function it_ignores_non_pjax_requests(
        FilterResponseEvent $event,
        Request $request,
        RequestMatcherInterface $pjaxRequestMatcher
    ) {
        $pjaxRequestMatcher->matches($request)->willReturn(false);
        $event->getResponse()->shouldNotBeCalled();

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
        MainContentMapper $mapper,
        Response $response
    ) {
        $mapper->map($response)->shouldBeCalled();
        $this->mapPjaxResponseToMainContent($event);
    }
}
