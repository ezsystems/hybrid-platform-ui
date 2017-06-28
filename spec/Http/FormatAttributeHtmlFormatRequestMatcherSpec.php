<?php

namespace spec\EzSystems\HybridPlatformUi\Http;

use EzSystems\HybridPlatformUi\Http\HtmlFormatRequestMatcher;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

class HtmlFormatRequestMatcherSpec extends ObjectBehavior
{
    function let(
        ParameterBag $attributes,
        Request $request
    ) {
        $request->attributes = $attributes;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(HtmlFormatRequestMatcher::class);
        $this->shouldHaveType(RequestMatcherInterface::class);
    }

    function it_matches_requests_with_html_format(
        ParameterBag $attributes,
        Request $request
    ) {
        $attributes->get('_format')->willReturn('html');
        $this->match($request)->shouldBe(true);
    }

    function it_does_not_match_requests_with_html_format(
        ParameterBag $attributes,
        Request $request
    ) {
        $attributes->get('_format')->willReturn('js');

        $this->match($request)->shouldBe(false);
    }
}
