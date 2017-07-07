<?php

namespace spec\EzSystems\HybridPlatformUi\Builder;

use EzSystems\HybridPlatformUi\Builder\NotificationBuilder;
use PhpSpec\ObjectBehavior;
use Twig\Environment;

class NotificationBuilderFactorySpec extends ObjectBehavior
{
    function let(Environment $environment)
    {
        $this->beConstructedWith($environment);
    }

    function it_creates_a_new_builder()
    {
        $this->create()->shouldHaveType(NotificationBuilder::class);
    }
}
