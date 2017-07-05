<?php

namespace spec\EzSystems\HybridPlatformUi\App;

use EzSystems\HybridPlatformUi\App\RequestAppResponseRenderer;
use EzSystems\HybridPlatformUi\Http\AjaxUpdateRequestMatcher;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestAppResponseRendererSpec extends ObjectBehavior
{
    function let(
        RequestStack $requestStack,
        AjaxUpdateRequestMatcher $ajaxUpdateRequestMatcher
    ) {
        $this->beConstructedWith($requestStack, $ajaxUpdateRequestMatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RequestAppResponseRenderer::class);
    }
}
