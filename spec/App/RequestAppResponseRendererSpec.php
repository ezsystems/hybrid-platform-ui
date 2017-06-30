<?php

namespace spec\EzSystems\HybridPlatformUi\App;

use EzSystems\HybridPlatformUi\App\RequestAppResponseRenderer;
use EzSystems\HybridPlatformUi\Http\AjaxUpdateRequestMatcher;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;

class RequestAppResponseRendererSpec extends ObjectBehavior
{
    function let(
        Request $request,
        AjaxUpdateRequestMatcher $ajaxUpdateRequestMatcher
    ) {
        $this->beConstructedWith($request, $ajaxUpdateRequestMatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RequestAppResponseRenderer::class);
    }
}
