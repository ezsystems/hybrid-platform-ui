<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace spec\EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\Core\Repository\Values\Content\Location;
use EzSystems\HybridPlatformUi\Repository\PathService;
use EzSystems\HybridPlatformUi\Repository\Values\Content\UiLocation;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UiLocationServiceSpec extends ObjectBehavior
{
    function let(LocationService $locationService, PathService $pathService)
    {
        $this->beConstructedWith($locationService, $pathService);
    }

    function it_loads_ui_locations_with_a_child_count(LocationService $locationService)
    {
        $contentInfo = new ContentInfo(['mainLocationId' => 1]);

        $location = new Location(['id' => 2]);
        $locationService->loadLocations($contentInfo)->willReturn([$location]);
        $locationService->getLocationChildCount($location)->willReturn(1);

        $uiLocation = new UiLocation(['childCount' => 1], $location);

        $this->loadLocations($contentInfo)->shouldBeLike([$uiLocation]);
    }

    function it_loads_ui_locations_with_a_path_location(LocationService $locationService, PathService $pathService)
    {
        $contentInfo = new ContentInfo(['mainLocationId' => 1]);

        $location = new Location(['id' => 2]);
        $locationService->loadLocations($contentInfo)->willReturn([$location]);
        $locationService->getLocationChildCount($location)->willReturn(1);
        $pathService->loadPathLocations(Argument::type(Location::class))->willReturn([$location]);

        $uiLocation = new UiLocation(['childCount' => 1, 'pathLocations' => [$location]], $location);

        $this->loadLocations($contentInfo)->shouldBeLike([$uiLocation]);
    }

    function it_loads_ui_locations_with_a_main_flag(LocationService $locationService)
    {
        $contentInfo = new ContentInfo(['mainLocationId' => 1]);

        $location = new Location(['id' => 1]);
        $locationService->loadLocations($contentInfo)->willReturn([$location]);
        $locationService->getLocationChildCount($location)->willReturn(1);

        $uiLocation = new UiLocation(['childCount' => 1, 'main' => true], $location);

        $this->loadLocations($contentInfo)->shouldBeLike([$uiLocation]);
    }

    function it_puts_the_main_location_first(LocationService $locationService)
    {
        $contentInfo = new ContentInfo(['mainLocationId' => 1]);

        $location = new Location(['id' => 2]);
        $mainLocation = new Location(['id' => 1]);

        $locationService->loadLocations($contentInfo)->willReturn([$location, $mainLocation, $location]);
        $locationService->getLocationChildCount($location)->willReturn(1);
        $locationService->getLocationChildCount($mainLocation)->willReturn(1);

        $uiLocation = new UiLocation(['childCount' => 1, 'main' => false], $location);

        $mainLocationDecorator = new UiLocation(['childCount' => 1, 'main' => true], $mainLocation);

        $this->loadLocations($contentInfo)->shouldBeLike([$mainLocationDecorator, $uiLocation, $uiLocation]);
    }
}
