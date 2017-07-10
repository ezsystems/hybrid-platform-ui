<?php

namespace spec\EzSystems\HybridPlatformUi\Mapper\Form\Location;

use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\LocationUpdateStruct;
use eZ\Publish\Core\Repository\Values\Content\Location;
use PhpSpec\ObjectBehavior;

class OrderingMapperSpec extends ObjectBehavior
{
    function let(LocationService $locationService)
    {
        $this->beConstructedWith($locationService);
    }

    function it_should_map_ordering_fields_to_form(
        LocationService $locationService
    ) {
        $updateStruct = new LocationUpdateStruct();
        $locationService->newLocationUpdateStruct()->willReturn($updateStruct);

        $sortField = 2;
        $sortOrder = 1;

        $location = new Location([
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ]);

        $expectedUpdateStruct = new LocationUpdateStruct();
        $expectedUpdateStruct->sortOrder = $sortOrder;
        $expectedUpdateStruct->sortField = $sortField;

        $this->mapToForm($location)->shouldBeLike($expectedUpdateStruct);
    }
}
