<?php

namespace spec\EzSystems\HybridPlatformUi\EventSubscriber;

use EzSystems\HybridPlatformUi\EventSubscriber\ComponentRendererSubscriber;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

class ComponentRendererSubscriberSpec extends ObjectBehavior
{
    function let(RequestMatcherInterface $ajaxUpdateRequestMatcher)
    {
        $this->beConstructedWith($ajaxUpdateRequestMatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ComponentRendererSubscriber::class);
    }
}
