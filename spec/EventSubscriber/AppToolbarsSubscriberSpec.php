<?php

namespace spec\EzSystems\HybridPlatformUi\EventSubscriber;

use EzSystems\HybridPlatformUi\EventSubscriber\AppToolbarsSubscriber;
use PhpSpec\ObjectBehavior;

class AppToolbarsSubscriberSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(AppToolbarsSubscriber::class);
    }
}
