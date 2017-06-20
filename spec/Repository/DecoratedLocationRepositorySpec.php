<?php

namespace spec\EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\Core\Repository\Values\Content\Location;
use EzSystems\HybridPlatformUi\Decorator\LocationDecorator;
use PhpSpec\ObjectBehavior;

class DecoratedLocationRepositorySpec extends ObjectBehavior
{
    function let(LocationService $locationService)
    {
        $this->beConstructedWith($locationService);
    }

    function it_loads_and_decorates_locations_with_a_child_count(LocationService $locationService, ContentInfo $contentInfo)
    {
        $location = new Location();
        $locationService->loadLocations($contentInfo)->willReturn([$location]);
        $locationService->getLocationChildCount($location)->willReturn(1);
        $locationDecorator = new LocationDecorator($location);
        $locationDecorator->childCount = 1;

        $this->loadLocations($contentInfo)->shouldBeLike([$locationDecorator]);
    }
}
