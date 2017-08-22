<?php

namespace spec\EzSystems\HybridPlatformUi\Paginator;

use EzSystems\HybridPlatformUi\Paginator\PagerInterface;
use Pagerfanta\Adapter\AdapterInterface;
use PhpSpec\ObjectBehavior;

class PagerSpec extends ObjectBehavior
{
    function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    function it_is_a_type_of_pager()
    {
        $this->shouldHaveType(PagerInterface::class);
    }
}
