<?php

namespace spec\EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\Core\Repository\Values\Content\Location;
use EzSystems\HybridPlatformUi\Decorator\LocationDecorator;
use EzSystems\HybridPlatformUi\Repository\PathService;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DecoratedLocationServiceSpec extends ObjectBehavior
{
    function let(LocationService $locationService, PathService $pathService)
    {
        $this->beConstructedWith($locationService, $pathService);
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


    function it_loads_and_decorates_with_a_path_location(LocationService $locationService, PathService $pathService, ContentInfo $contentInfo)
    {
        $location = new Location();
        $locationService->loadLocations($contentInfo)->willReturn([$location]);
        $locationService->getLocationChildCount($location)->willReturn(1);
        $pathService->loadPathLocations(Argument::type(Location::class))->willReturn([$location]);

        $locationDecorator = new LocationDecorator($location);
        $locationDecorator->childCount = 1;
        $locationDecorator->pathLocations = [$location];

        $this->loadLocations($contentInfo)->shouldBeLike([$locationDecorator]);
    }
}
