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
            new Location(['id' => $firstLocationId, 'hidden' => false]),
            new Location(['id' => $secondLocationId, 'hidden' => false]),
        ];

        $expectedData = [
            'removeLocations' => [
                $firstLocationId => false,
                $secondLocationId => false,
            ],
            'locationVisibility' => [
                $firstLocationId => true,
                $secondLocationId => true,
            ],
        ];

        $this->mapToForm($locations)->shouldBeLike($expectedData);
    }

    function it_should_map_location_visibility_to_form()
    {
        $firstLocationId = 1;
        $secondLocationId = 2;

        $locations = [
            new Location(['id' => $firstLocationId, 'hidden' => false]),
            new Location(['id' => $secondLocationId, 'hidden' => true]),
        ];

        $expectedData = [
            'removeLocations' => [
                $firstLocationId => false,
                $secondLocationId => false,
            ],
            'locationVisibility' => [
                $firstLocationId => true,
                $secondLocationId => false,
            ],
        ];

        $this->mapToForm($locations)->shouldBeLike($expectedData);
    }
}
