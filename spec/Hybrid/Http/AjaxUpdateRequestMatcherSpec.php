<?php

namespace spec\EzSystems\PlatformUIBundle\Hybrid\Http;

use EzSystems\PlatformUIBundle\Hybrid\Http\AjaxUpdateRequestMatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

class AjaxUpdateRequestMatcherSpec extends ObjectBehavior
{
    function let(
        HeaderBag $headerBag,
        Request $request
    ) {
        $request->headers = $headerBag;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AjaxUpdateRequestMatcher::class);
        $this->shouldHaveType(RequestMatcherInterface::class);
    }

    function it_matches_requests_with_the_x_ajax_update_header(
        HeaderBag $headerBag,
        Request $request
    ) {
        $headerBag->get('x-ajax-update', 0)->willReturn(1);
        $this->matches($request)->shouldBe(true);
    }

    function it_does_not_match_requests_with_the_x_ajax_update_header(
        HeaderBag $headerBag,
        Request $request
    ) {
        $headerBag->get('x-ajax-update', 0)->willReturn(0);
        $this->matches($request)->shouldBe(false);
    }
}
