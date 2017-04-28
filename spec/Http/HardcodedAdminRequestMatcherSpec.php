<?php

namespace spec\EzSystems\HybridPlatformUi\Http;

use EzSystems\HybridPlatformUi\Http\HardcodedAdminRequestMatcher;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

/**
 * @method bool matches(Request $request)
 */
class HardcodedAdminRequestMatcherSpec extends ObjectBehavior
{
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
    }
}
