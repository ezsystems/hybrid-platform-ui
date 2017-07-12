<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace spec\EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentException;
use eZ\Publish\API\Repository\Values\Content\LocationCreateStruct;
use eZ\Publish\Core\Repository\Values\Content\Location;
use eZ\Publish\Core\Repository\Values\ContentType\ContentType;
use EzSystems\HybridPlatformUi\Repository\PathService;
use EzSystems\HybridPlatformUi\Repository\Permission\UiPermissionResolver;
use EzSystems\HybridPlatformUi\Repository\Values\Content\UiLocation;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UiLocationServiceSpec extends ObjectBehavior
{
    function let(
        LocationService $locationService,
        PathService $pathService,
        UiPermissionResolver $permissionResolver,
        ContentTypeService $contentTypeService
    ) {
        $this->beConstructedWith($locationService, $pathService, $permissionResolver, $contentTypeService);
    }

    function it_loads_ui_locations_with_a_child_count(LocationService $locationService)
    {
        $contentInfo = new ContentInfo(['mainLocationId' => 1]);

        $location = new Location([
            'id' => 2,
            'contentInfo' => $contentInfo,
        ]);

        $locationService->loadLocations($contentInfo)->willReturn([$location]);
        $locationService->getLocationChildCount($location)->willReturn(1);

        $uiLocation = new UiLocation($location, ['childCount' => 1]);

        $this->loadLocations($contentInfo)->shouldBeLike([$uiLocation]);
    }

    function it_loads_ui_locations_with_a_path_location(LocationService $locationService, PathService $pathService)
    {
        $contentInfo = new ContentInfo(['mainLocationId' => 1]);

        $location = new Location([
            'id' => 2,
            'contentInfo' => $contentInfo,
        ]);

        $locationService->loadLocations($contentInfo)->willReturn([$location]);
        $locationService->getLocationChildCount($location)->willReturn(1);
        $pathService->loadPathLocations(Argument::type(Location::class))->willReturn([$location]);

        $uiLocation = new UiLocation($location, ['childCount' => 1, 'pathLocations' => [$location]]);

        $this->loadLocations($contentInfo)->shouldBeLike([$uiLocation]);
    }

    function it_puts_the_main_location_first(LocationService $locationService)
    {
        $contentInfo = new ContentInfo(['mainLocationId' => 1]);

        $location = new Location([
            'id' => 2,
            'contentInfo' => $contentInfo,
        ]);

        $mainLocation = new Location([
            'id' => 1,
            'contentInfo' => $contentInfo,
        ]);

        $locationService->loadLocations($contentInfo)->willReturn([$location, $mainLocation, $location]);
        $locationService->getLocationChildCount($location)->willReturn(1);
        $locationService->getLocationChildCount($mainLocation)->willReturn(1);

        $uiLocation = new UiLocation($location, ['childCount' => 1]);

        $uiMainLocation = new UiLocation($mainLocation, ['childCount' => 1, 'main' => true]);

        $this->loadLocations($contentInfo)->shouldBeLike([$uiMainLocation, $uiLocation, $uiLocation]);
    }

    function it_deletes_locations(LocationService $locationService)
    {
        $mainLocationId = 1;
        $deleteLocationId = 2;

        $contentInfo = new ContentInfo(['mainLocationId' => $mainLocationId]);

        $location = new Location([
            'id' => $deleteLocationId,
            'contentInfo' => $contentInfo,
        ]);

        $locationService->loadLocation($deleteLocationId)->willReturn($location);
        $locationService->deleteLocation($location)->shouldBeCalled();

        $this->deleteLocations([$deleteLocationId]);
    }

    function it_loads_ui_locations_with_user_access_flags(
        LocationService $locationService,
        UiPermissionResolver $permissionResolver
    ) {
        $contentInfo = new ContentInfo(['mainLocationId' => 1]);

        $location = new Location([
            'id' => 2,
            'contentInfo' => $contentInfo,
        ]);

        $locationService->loadLocations($contentInfo)->willReturn([$location]);
        $locationService->getLocationChildCount($location)->willReturn(1);

        $permissionResolver->canManageLocations($location->getContentInfo())->willReturn(true);
        $permissionResolver->canRemoveContent($location->getContentInfo(), $location)->willReturn(true);

        $uiLocation = new UiLocation(
            $location,
            [
                'childCount' => 1,
                'userCanManage' => true,
                'userCanRemove' => true,
            ]
        );

        $this->loadLocations($contentInfo)->shouldBeLike([$uiLocation]);
    }

    function it_adds_a_location(LocationService $locationService, ContentInfo $contentInfo, LocationCreateStruct $locationCreateStruct)
    {
        $parentLocationId = 2;
        $locationService->newLocationCreateStruct($parentLocationId)->willReturn($locationCreateStruct);
        $locationService->createLocation($contentInfo, $locationCreateStruct)->shouldBeCalled();

        $this->addLocation($contentInfo, $parentLocationId);
    }

    function it_cannot_swap_a_non_container_with_a_container(
        LocationService $locationService,
        ContentTypeService $contentTypeService
    ) {
        $currentLocationId = 1;
        $newLocationId = 5;
        $contentTypeId = 2;

        $location = new Location(['id' => $currentLocationId]);
        $contentInfo = new ContentInfo(['contentTypeId' => $contentTypeId]);
        $newLocation = new Location(['id' => $newLocationId, 'contentInfo' => $contentInfo]);
        $contentType = new ContentType(['isContainer' => false]);

        $locationService->loadLocation($newLocationId)->willReturn($newLocation);
        $locationService->getLocationChildCount($location)->willReturn(1);

        $contentTypeService->loadContentType($contentTypeId)->willReturn($contentType);

        $locationService->swapLocation($location, $newLocation)->shouldNotBeCalled();

        $this->shouldThrow(InvalidArgumentException::class)->duringSwapLocations($location, $newLocationId);
    }

    function it_can_swap_a_location(
        LocationService $locationService,
        ContentTypeService $contentTypeService
    ) {
        $currentLocationId = 1;
        $newLocationId = 5;
        $contentTypeId = 2;

        $location = new Location(['id' => $currentLocationId]);
        $contentInfo = new ContentInfo(['contentTypeId' => $contentTypeId]);
        $newLocation = new Location(['id' => $newLocationId, 'contentInfo' => $contentInfo]);
        $contentType = new ContentType(['isContainer' => true]);

        $locationService->loadLocation($newLocationId)->willReturn($newLocation);
        $locationService->getLocationChildCount($location)->willReturn(1);

        $contentTypeService->loadContentType($contentTypeId)->willReturn($contentType);

        $locationService->swapLocation($location, $newLocation)->shouldBeCalled();

        $this->swapLocations($location, $newLocationId);
    }
}
