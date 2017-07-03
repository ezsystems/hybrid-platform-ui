<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace spec\EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\Core\Base\Exceptions\ForbiddenException;
use eZ\Publish\Core\Repository\Values\Content\Location;
use EzSystems\HybridPlatformUi\Repository\PathService;
use EzSystems\HybridPlatformUi\Repository\Values\Content\UiLocation;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UiLocationServiceSpec extends ObjectBehavior
{
    function let(LocationService $locationService, ContentService $contentService, PathService $pathService)
    {
        $this->beConstructedWith($locationService, $contentService, $pathService);
    }

    function it_loads_ui_locations_with_a_child_count(LocationService $locationService)
    {
        $contentInfo = new ContentInfo(['mainLocationId' => 1]);

        $location = new Location(['id' => 2]);
        $locationService->loadLocations($contentInfo)->willReturn([$location]);
        $locationService->getLocationChildCount($location)->willReturn(1);

        $uiLocation = new UiLocation($location, ['childCount' => 1]);

        $this->loadLocations($contentInfo)->shouldBeLike([$uiLocation]);
    }

    function it_loads_ui_locations_with_a_path_location(LocationService $locationService, PathService $pathService)
    {
        $contentInfo = new ContentInfo(['mainLocationId' => 1]);

        $location = new Location(['id' => 2]);
        $locationService->loadLocations($contentInfo)->willReturn([$location]);
        $locationService->getLocationChildCount($location)->willReturn(1);
        $pathService->loadPathLocations(Argument::type(Location::class))->willReturn([$location]);

        $uiLocation = new UiLocation($location, ['childCount' => 1, 'pathLocations' => [$location]]);

        $this->loadLocations($contentInfo)->shouldBeLike([$uiLocation]);
    }

    function it_loads_ui_locations_with_a_main_flag(LocationService $locationService)
    {
        $contentInfo = new ContentInfo(['mainLocationId' => 1]);

        $location = new Location(['id' => 1]);
        $locationService->loadLocations($contentInfo)->willReturn([$location]);
        $locationService->getLocationChildCount($location)->willReturn(1);

        $uiLocation = new UiLocation($location, ['childCount' => 1, 'main' => true]);

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

        $uiLocation = new UiLocation($location, ['childCount' => 1, 'main' => false]);

        $mainLocationDecorator = new UiLocation($mainLocation, ['childCount' => 1, 'main' => true]);

        $this->loadLocations($contentInfo)->shouldBeLike([$mainLocationDecorator, $uiLocation, $uiLocation]);
    }

    function it_deletes_locations(LocationService $locationService, ContentService $contentService)
    {
        $mainLocationId = 1;
        $contentId = 1;
        $deleteLocationId = 2;

        $contentInfo = new ContentInfo(['mainLocationId' => $mainLocationId]);
        $contentService->loadContentInfo($contentId)->willReturn($contentInfo);

        $location = new Location(['id' => $deleteLocationId]);
        $locationService->loadLocation($deleteLocationId)->willReturn($location);
        $locationService->deleteLocation($location)->shouldBeCalled();

        $this->deleteLocations([$deleteLocationId], $contentId);
    }

    function it_does_not_delete_the_main_location(LocationService $locationService, ContentService $contentService)
    {
        $mainLocationId = 1;
        $contentId = 1;

        $contentInfo = new ContentInfo(['mainLocationId' => $mainLocationId]);
        $contentService->loadContentInfo($contentId)->willReturn($contentInfo);

        $location = new Location(['id' => $mainLocationId]);
        $locationService->loadLocation($mainLocationId)->willReturn($location);
        $locationService->deleteLocation($location)->shouldNotBeCalled();

        $this->shouldThrow(ForbiddenException::class)->duringDeleteLocations([$mainLocationId], $contentId);
    }
}
