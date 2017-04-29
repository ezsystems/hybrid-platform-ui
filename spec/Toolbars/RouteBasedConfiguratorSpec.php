<?php

namespace spec\EzSystems\HybridPlatformUi\Toolbars;

use EzSystems\HybridPlatformUi\Toolbars\RouteBasedConfigurator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RouteBasedConfiguratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(RouteBasedConfigurator::class);
    }
}
