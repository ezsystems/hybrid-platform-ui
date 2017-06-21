<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
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

    function it_loads_and_decorates_locations_with_a_child_count(LocationService $locationService)
    {
        $contentInfo = new ContentInfo(['mainLocationId' => 1]);

        $location = new Location(['id' => 2]);
        $locationService->loadLocations($contentInfo)->willReturn([$location]);
        $locationService->getLocationChildCount($location)->willReturn(1);

        $locationDecorator = new LocationDecorator($location);
        $locationDecorator->childCount = 1;
        $locationDecorator->main = false;

        $this->loadLocations($contentInfo)->shouldBeLike([$locationDecorator]);
    }

    function it_loads_and_decorates_locations_with_a_path_location(LocationService $locationService, PathService $pathService)
    {
        $contentInfo = new ContentInfo(['mainLocationId' => 1]);

        $location = new Location(['id' => 2]);
        $locationService->loadLocations($contentInfo)->willReturn([$location]);
        $locationService->getLocationChildCount($location)->willReturn(1);
        $pathService->loadPathLocations(Argument::type(Location::class))->willReturn([$location]);

        $locationDecorator = new LocationDecorator($location);
        $locationDecorator->childCount = 1;
        $locationDecorator->pathLocations = [$location];
        $locationDecorator->main = false;

        $this->loadLocations($contentInfo)->shouldBeLike([$locationDecorator]);
    }

    function it_loads_and_decorates_locations_with_a_main_flag(LocationService $locationService)
    {
        $contentInfo = new ContentInfo(['mainLocationId' => 1]);

        $location = new Location(['id' => 1]);
        $locationService->loadLocations($contentInfo)->willReturn([$location]);
        $locationService->getLocationChildCount($location)->willReturn(1);

        $locationDecorator = new LocationDecorator($location);
        $locationDecorator->childCount = 1;
        $locationDecorator->main = true;

        $this->loadLocations($contentInfo)->shouldBeLike([$locationDecorator]);
    }

    function it_puts_the_main_location_first(LocationService $locationService)
    {
        $contentInfo = new ContentInfo(['mainLocationId' => 1]);

        $location = new Location(['id' => 2]);
        $mainLocation = new Location(['id' => 1]);

        $locationService->loadLocations($contentInfo)->willReturn([$location, $mainLocation, $location]);
        $locationService->getLocationChildCount($location)->willReturn(1);
        $locationService->getLocationChildCount($mainLocation)->willReturn(1);

        $locationDecorator = new LocationDecorator($location);
        $locationDecorator->childCount = 1;
        $locationDecorator->main = false;

        $mainLocationDecorator = new LocationDecorator($mainLocation);
        $mainLocationDecorator->childCount = 1;
        $mainLocationDecorator->main = true;

        $this->loadLocations($contentInfo)->shouldBeLike([$mainLocationDecorator, $locationDecorator, $locationDecorator]);
    }
}
