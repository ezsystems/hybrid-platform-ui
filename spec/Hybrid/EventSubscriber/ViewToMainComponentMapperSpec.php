<?php

namespace spec\EzSystems\PlatformUIBundle\Hybrid\EventSubscriber;

use EzSystems\PlatformUIBundle\Hybrid\EventSubscriber\ViewToMainComponentMapper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Templating\EngineInterface;

class ViewToMainComponentMapperSpec extends ObjectBehavior
{
    function let(
        EngineInterface $templating,
        RequestMatcherInterface $adminRequestMatcher)
    {
        $adminRequestMatcher->matches(Argument::type(Request::class))->willReturn(true);
        $this->beConstructedWith($templating, $adminRequestMatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ViewToMainComponentMapper::class);
        $this->shouldHaveType(EventSubscriberInterface::class);
    }

    function it_subscribes_to_the_view_KernelEvent()
    {
        $this->getSubscribedEvents()->shouldBeArray();
        $this->getSubscribedEvents()->shouldSubscribeToEvent(KernelEvents::VIEW);
    }

    function it_has_higher_priority_than_the_core_view_renderer_listener()
    {
        $this->getSubscribedEvents()->shouldHaveHigherPriorityThan(0);
    }

    public function getMatchers()
    {
        return [
            'subscribeToEvent' => function (array $subscribedEvents, $event) {
                return isset($subscribedEvents[$event])
                    && is_array($subscribedEvents[$event])
                    && count($subscribedEvents[$event]) === 1;
            },
            'havePriorityHigherThan' => function (array $subscribedEvents, $priority) {
                $event = $subscribedEvents[KernelEvents::VIEW][0];
                return isset($event[1]) && $event[1] > $priority;
            }
        ];
    }
}
