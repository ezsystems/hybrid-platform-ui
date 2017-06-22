<?php

namespace spec\EzSystems\HybridPlatformUi\Http;

use EzSystems\HybridPlatformUi\Http\HeaderAjaxUpdateRequestMatcher;
use PhpSpec\ObjectBehavior;
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
        $this->shouldHaveType(HeaderAjaxUpdateRequestMatcher::class);
        $this->shouldHaveType(RequestMatcherInterface::class);
    }

    function it_matches_requests_with_the_x_ajax_update_header(
        HeaderBag $headerBag,
        Request $request
    ) {
        $headerBag->has('x-ajax-update')->willReturn(true);
        $this->matches($request)->shouldBe(true);
    }

    function it_does_not_match_requests_with_the_x_ajax_update_header(
        HeaderBag $headerBag,
        Request $request
    ) {
        $headerBag->has('x-ajax-update')->willReturn(false);
        $this->matches($request)->shouldBe(false);
    }
}
