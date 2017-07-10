<?php

namespace spec\EzSystems\HybridPlatformUi\Builder;

use EzSystems\HybridPlatformUi\Builder\NotificationBuilder;
use PhpSpec\ObjectBehavior;

class NotificationBuilderFactorySpec extends ObjectBehavior
{
    function it_creates_a_new_builder()
    {
        $this->create()->shouldHaveType(NotificationBuilder::class);
    }
}
