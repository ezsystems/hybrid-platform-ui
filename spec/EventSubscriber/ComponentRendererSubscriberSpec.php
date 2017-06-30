<?php

namespace spec\EzSystems\HybridPlatformUi\EventSubscriber;

use EzSystems\HybridPlatformUi\EventSubscriber\ComponentRendererSubscriber;
use EzSystems\HybridPlatformUi\Http\AjaxUpdateRequestMatcher;
use PhpSpec\ObjectBehavior;

class ComponentRendererSubscriberSpec extends ObjectBehavior
{
    function let(AjaxUpdateRequestMatcher $ajaxUpdateRequestMatcher)
    {
        $this->beConstructedWith($ajaxUpdateRequestMatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ComponentRendererSubscriber::class);
    }
}
