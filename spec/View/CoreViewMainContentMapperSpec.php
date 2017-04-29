<?php

namespace spec\EzSystems\HybridPlatformUi\View;

use EzSystems\HybridPlatformUi\Components\MainContent;
use EzSystems\HybridPlatformUi\View\CoreViewMainContentMapper;
use PhpSpec\ObjectBehavior;

class CoreViewMainContentMapperSpec extends ObjectBehavior
{
    function let(MainContent $mainContent)
    {
        $this->beConstructedWith($mainContent);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CoreViewMainContentMapper::class);
    }
}
