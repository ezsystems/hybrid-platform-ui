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
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\SearchResult;
use eZ\Publish\Core\Repository\Values\Content\Location;
use eZ\Publish\Core\Repository\Values\Content\TrashItem;
use eZ\Publish\Core\Repository\Values\ContentType\ContentType;
use EzSystems\HybridPlatformUi\Repository\PathService;
use EzSystems\HybridPlatformUi\Repository\UiTrashService;
use EzSystems\HybridPlatformUi\Repository\Values\Content\UiTrashItem;
use PhpSpec\ObjectBehavior;

class UiTrashServiceSpec extends ObjectBehavior
{
    function let(
        TrashService $trashService,
        ContentTypeService $contentTypeService,
        PathService $pathService,
        LocationService $locationService
    ) {
        $this->beConstructedWith(
            $trashService,
            $contentTypeService,
            $pathService,
            $locationService
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UiTrashService::class);
    }

    function it_should_get_the_TrashItems(
        TrashService $trashService,
        ContentTypeService $contentTypeService,
        PathService $pathService)
    {
        $contentTypeId = 'truc';
        $contentInfo = new ContentInfo(['contentTypeId' => $contentTypeId]);
        $trashItem = new TrashItem(['contentInfo' => $contentInfo]);
        $trashItems = [$trashItem];
        $searchResult = new SearchResult(['items' => $trashItems]);
        $path = [];
        $contentType = new ContentType();

        $trashService->findTrashItems(new Query())->willReturn($searchResult);

        $pathService->loadPathLocations($trashItem)->willReturn($path);

        $contentTypeService->loadContentType($contentTypeId)->willReturn($contentType);

        $uiTrashItems = $this->getTrashItems();

        /** @var UiTrashItem $uiTrashItem */
        $uiTrashItem = $uiTrashItems[0];

        $expectedTrashItem = new UiTrashItem(
            $trashItem,
            ['locationPath' => $path, 'contentType' => $contentType]
        );

        $uiTrashItem->shouldBeLike($expectedTrashItem);
        $uiTrashItem->contentType->shouldBe($contentType);
        $uiTrashItem->locationPath->shouldBe($path);
    }

    function it_empties_the_trash(TrashService $trashService)
    {
        $trashService->emptyTrash()->shouldBeCalled();
        $this->emptyTrash();
    }

    private function mockRecover($trashItemId, $trashService, $parentLocation = null)
    {
        $trashItem = new TrashItem(['id' => $trashItemId]);
        $trashService->loadTrashItem($trashItemId)->willReturn($trashItem);

        if ($parentLocation) {
            $trashService->recover($trashItem, $parentLocation)->shouldBeCalled();
        } else {
            $trashService->recover($trashItem)->shouldBeCalled();
        }
    }

    function it_restores_a_list_of_trash_item_to_their_original_location(TrashService $trashService)
    {
        $trashItemId1 = 333;
        $trashItemId2 = 555;

        $this->mockRecover($trashItemId1, $trashService);
        $this->mockRecover($trashItemId2, $trashService);

        $this->restoreTrashItems([$trashItemId1, $trashItemId2]);
    }

    function it_restores_a_list_of_trash_item_to_a_new_location(
        TrashService $trashService,
        LocationService $locationService)
    {
        $parentLocationId = 444;
        $parentLocation = new Location(['id' => $parentLocationId]);
        $locationService->loadLocation($parentLocationId)->willReturn($parentLocation);

        $trashItemId1 = 333;
        $trashItemId2 = 555;

        $this->mockRecover($trashItemId1, $trashService, $parentLocation);
        $this->mockRecover($trashItemId2, $trashService, $parentLocation);

        $this->restoreTrashItems([$trashItemId1, $trashItemId2], $parentLocationId);
    }

    function it_counts_the_number_of_trash_items(TrashService $trashService)
    {
        $query = new Query();
        $query->limit = 0;
        $searchResult = new SearchResult(['count' => 42]);
        $trashService->findTrashItems($query)->willReturn($searchResult);

        $this->countTrashItems()->shouldBe(42);
    }
}
