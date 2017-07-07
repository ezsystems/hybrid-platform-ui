<?php

namespace spec\EzSystems\HybridPlatformUi\Http\Response;

use EzSystems\HybridPlatformUi\Http\Response\NoRenderResponse;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Response;

class ResetResponseSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('/');
    }

    function it_is_a_response()
    {
        $this->shouldBeAnInstanceOf(Response::class);
    }

    function it_bypasses_app_render()
    {
        $this->shouldBeAnInstanceOf(NoRenderResponse::class);
    }

    function it_sends_a_reset_status_code()
    {
        $this->getStatusCode()->shouldReturn(Response::HTTP_RESET_CONTENT);
    }

    function it_sends_no_content()
    {
        $this->getContent()->shouldBe('');
    }
}
