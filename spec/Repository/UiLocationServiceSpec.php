<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace spec\EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\TrashService;
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
        TrashService $trashService,
        PathService $pathService,
        UiPermissionResolver $permissionResolver,
        ContentTypeService $contentTypeService
    ) {
        $this->beConstructedWith(
            $locationService,
            $trashService,
            $pathService,
            $permissionResolver,
            $contentTypeService
        );
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

    function it_trashes_a_single_location_and_returns_the_parent(
        LocationService $locationService,
        TrashService $trashService
    ) {
        $locationId = 333;
        $parentLocationId = 222;

        $location = new Location([
            'id' => $locationId,
            'parentLocationId' => $parentLocationId,
        ]);

        $parentLocation = new Location([
            'id' => $parentLocationId,
        ]);

        $trashService->trash($location)->shouldBeCalled();
        $locationService->loadLocation($parentLocationId)->willReturn($parentLocation);

        $this->trashLocationAndReturnParent($location)->shouldBeLike($parentLocation);
    }

    function it_tells_if_a_location_can_not_be_removed_because_it_is_the_root()
    {
        $location = new Location([
            'id' => 111,
            'depth' => 1,
        ]);

        $this->canRemoveLocation($location)->shouldReturn(false);
    }

    function it_tells_if_a_location_can_not_be_removed_because_user_is_not_allowed(
        UiPermissionResolver $permissionResolver
    ) {
        $contentInfo = new ContentInfo(['id' => 110]);

        $location = new Location([
            'id' => 111,
            'contentInfo' => $contentInfo,
        ]);

        $permissionResolver->canRemoveContent($contentInfo, $location)->willReturn(false);

        $this->canRemoveLocation($location)->shouldReturn(false);
    }

    function it_tells_if_a_location_can_be_removed(
        UiPermissionResolver $permissionResolver
    ) {
        $contentInfo = new ContentInfo(['id' => 110]);

        $location = new Location([
            'id' => 111,
            'contentInfo' => $contentInfo,
        ]);

        $permissionResolver->canRemoveContent($contentInfo, $location)->willReturn(true);

        $this->canRemoveLocation($location)->shouldReturn(true);
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

    function it_swaps_a_location_and_then_reloads(
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

        $locationService->loadLocation($currentLocationId)->willReturn($location);

        $this->swapLocations($location, $newLocationId)->shouldBe($location);
    }

    function it_moves_a_location(
        LocationService $locationService,
        ContentTypeService $contentTypeService
    ) {
        $currentLocationId = 1;
        $newParentLocationId = 5;
        $contentTypeId = 2;

        $location = new Location(['id' => $currentLocationId]);
        $contentInfo = new ContentInfo(['contentTypeId' => $contentTypeId]);
        $newParentLocation = new Location(['id' => $newParentLocationId, 'contentInfo' => $contentInfo]);
        $contentType = new ContentType(['isContainer' => true]);

        $locationService->loadLocation($newParentLocationId)->willReturn($newParentLocation);

        $contentTypeService->loadContentType($contentTypeId)->willReturn($contentType);

        $locationService->moveSubtree($location, $newParentLocation)->shouldBeCalled();
        $locationService->loadLocation($currentLocationId)->willReturn($location);

        $this->moveLocation($location, $newParentLocationId)->shouldBe($location);
    }

    function it_cannot_move_a_location_to_a_parent_that_is_not_a_container(
        LocationService $locationService,
        ContentTypeService $contentTypeService
    ) {
        $currentLocationId = 1;
        $newParentLocationId = 5;
        $contentTypeId = 2;

        $location = new Location(['id' => $currentLocationId]);
        $contentInfo = new ContentInfo(['contentTypeId' => $contentTypeId]);
        $newParentLocation = new Location(['id' => $newParentLocationId, 'contentInfo' => $contentInfo]);
        $contentType = new ContentType(['isContainer' => false]);

        $locationService->loadLocation($newParentLocationId)->willReturn($newParentLocation);

        $contentTypeService->loadContentType($contentTypeId)->willReturn($contentType);

        $locationService->moveSubtree($location, $newParentLocation)->shouldNotBeCalled();

        $this->shouldThrow(InvalidArgumentException::class)->duringMoveLocation(
            $location,
            $newParentLocationId
        );
    }

    function it_tells_if_a_location_can_not_be_moved_because_it_is_the_root()
    {
        $location = new Location([
            'id' => 111,
            'depth' => 1,
        ]);

        $this->canMoveLocation($location)->shouldReturn(false);
    }

    function it_tells_if_a_location_can_be_moved()
    {
        $contentInfo = new ContentInfo(['id' => 110]);

        $location = new Location([
            'id' => 111,
            'contentInfo' => $contentInfo,
        ]);

        $this->canMoveLocation($location)->shouldReturn(true);
    }
}
