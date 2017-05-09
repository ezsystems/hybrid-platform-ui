<?php

namespace spec\EzSystems\HybridPlatformUi\Http;

use EzSystems\HybridPlatformUi\Http\HardcodedAdminRequestMatcher;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

/**
 * @method bool matches(Request $request)
 */
class HardcodedAdminRequestMatcherSpec extends ObjectBehavior
{
    function let(Request $request, ParameterBag $requestAttributes)
    {
        $request->attributes = $requestAttributes;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(HardcodedAdminRequestMatcher::class);
        $this->shouldHaveType(RequestMatcherInterface::class);
    }

    function it_matches_requests_to_the_admin_siteaccess(
        Request $request
    ) {
        $request->getRequestUri()->willReturn('/admin/foo');
        $this->matches($request)->shouldEqual(true);

        $request->getRequestUri()->willReturn('/other/path');
        $this->matches($request)->shouldEqual(false);
    }

    function it_ignores_requests_with_an_excluded_route_prefix(
        ParameterBag $requestAttributes,
        Request $request
    ) {
        $request->getRequestUri()->willReturn('/admin/foo');
        $this->setExcludedRoutesPrefixes(['excluded_']);

        $requestAttributes->get('_route')->willReturn('excluded_route');
        $this->matches($request)->shouldEqual(false);

        $requestAttributes->get('_route')->willReturn('another_route');
        $this->matches($request)->shouldEqual(true);
    }
}
