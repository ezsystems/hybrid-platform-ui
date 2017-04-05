<?php

namespace spec\EzSystems\HybridPlatformUi\Http;

use EzSystems\HybridPlatformUi\Http\ChainRequestMatcher;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

class ChainRequestMatcherSpec extends ObjectBehavior
{
    function let(
        RequestMatcherInterface $firstRequestMatcher,
        RequestMatcherInterface $secondRequestMatcher
    ) {
        $this->beConstructedWith($firstRequestMatcher, $secondRequestMatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ChainRequestMatcher::class);
        $this->shouldHaveType(RequestMatcherInterface::class);
    }

    function it_can_be_constructed_with_any_number_of_request_matchers(
        RequestMatcherInterface $firstRequestMatcher,
        RequestMatcherInterface $secondRequestMatcher,
        RequestMatcherInterface $thirdRequestMatcher,
        RequestMatcherInterface $fourthRequestMatcher
    ) {
        $this->beConstructedWith(
            $firstRequestMatcher,
            $secondRequestMatcher,
            $thirdRequestMatcher,
            $fourthRequestMatcher
        );
    }

    function it_matches_if_all_matchers_match(
        Request $request,
        RequestMatcherInterface $firstRequestMatcher,
        RequestMatcherInterface $secondRequestMatcher
    ) {
        $firstRequestMatcher->matches($request)->willReturn(true);
        $secondRequestMatcher->matches($request)->willReturn(true);

        $this->matches($request)->shouldBe(true);
    }

    function it_does_not_match_if_at_least_one_matcher_does_not_match(
        Request $request,
        RequestMatcherInterface $firstRequestMatcher,
        RequestMatcherInterface $secondRequestMatcher
    ) {
        $firstRequestMatcher->matches($request)->willReturn(true);
        $secondRequestMatcher->matches($request)->willReturn(false);

        $this->matches($request)->shouldBe(false);
    }
}
