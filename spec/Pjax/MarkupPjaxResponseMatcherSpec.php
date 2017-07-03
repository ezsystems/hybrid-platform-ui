<?php

namespace spec\EzSystems\HybridPlatformUi\Pjax;

use EzSystems\HybridPlatformUi\Http\ResponseMatcherInterface;
use EzSystems\HybridPlatformUi\Pjax\MarkupPjaxResponseMatcher;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Response;

class MarkupPjaxResponseMatcherSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(MarkupPjaxResponseMatcher::class);
        $this->shouldHaveType(ResponseMatcherInterface::class);
    }

    function it_matches_responses_that_have_the_pjax_markup_as_content(
        Response $response
    ) {
        $response->getContent()->willReturn('_fixtures/pjax_response.html');

        $this->matches($response)->shouldBe(false);
    }

    function it_matches_responses_that_does_not_have_the_pjax_markup_as_content(
        Response $response
    ) {
        $response->getContent()->willReturn('_fixtures/lambda.html');

        $this->matches($response)->shouldBe(false);
    }
}
