<?php

namespace spec\EzSystems\HybridPlatformUi\Mapper\Form\Location;

use eZ\Publish\Core\Repository\Values\Content\Location;
use PhpSpec\ObjectBehavior;

class OrderingMapperSpec extends ObjectBehavior
{
    function it_should_map_ordering_fields_to_form()
    {
        $sortField = 2;
        $sortOrder = 1;

        $location = new Location([
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ]);

        $expectedData = [
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ];

        $this->mapToForm($location)->shouldBeLike($expectedData);
    }
}
