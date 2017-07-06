<?php

namespace spec\EzSystems\HybridPlatformUi\Mapper\Form;

use eZ\Publish\Core\Repository\Values\Content\Location;
use PhpSpec\ObjectBehavior;

class LocationMapperSpec extends ObjectBehavior
{
    function it_should_map_locations_to_form()
    {
        $firstLocationId = 1;
        $secondLocationId = 2;

        $locations = [
            new Location(['id' => $firstLocationId]),
            new Location(['id' => $secondLocationId]),
        ];

        $expectedData = [
            'removeLocations' => [
                $firstLocationId => false,
                $secondLocationId => false,
            ],
        ];

        $this->mapToForm($locations)->shouldBeLike($expectedData);
    }
}
