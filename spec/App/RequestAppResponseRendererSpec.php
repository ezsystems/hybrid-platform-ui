<?php

namespace spec\EzSystems\HybridPlatformUi\App;

use EzSystems\HybridPlatformUi\App\RequestAppResponseRenderer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

class RequestAppResponseRendererSpec extends ObjectBehavior
{
    function let(
        Request $request,
        RequestMatcherInterface $ajaxUpdateRequestMatcher
    ) {
        $this->beConstructedWith($request, $ajaxUpdateRequestMatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RequestAppResponseRenderer::class);
    }
}
