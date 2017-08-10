<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\TrashService;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\TrashItem;
use EzSystems\HybridPlatformUi\Repository\Values\Content\UiTrashItem;

/**
 * Class UiTrashService.
 *
 * Provides methods to help displaying and managing the Trash
 */
class UiTrashService
{
    /** @var TrashService */
    private $trashService;

    /** @var PathService */
    private $pathService;

    /** @var ContentTypeService */
    private $contentTypeService;

    /** @var LocationService */
    private $locationService;

    public function __construct(
        TrashService $trashService,
        ContentTypeService $contentTypeService,
        PathService $pathService,
        LocationService $locationService)
    {
        $this->trashService = $trashService;
        $this->contentTypeService = $contentTypeService;
        $this->pathService = $pathService;
        $this->locationService = $locationService;
    }

    /**
     * Provides the list of all TrashItems as UiTrashItems.
     *
     * @return UiTrashItem[]
     */
    public function getTrashItems()
    {
        $uiTrashItems = [];

        $query = new Query();
        $trashItems = $this->trashService->findTrashItems($query)->items;

        /** @var TrashItem $trashItem */
        foreach ($trashItems as $trashItem) {
            $uiTrashItems[] = $this->createUiTrashItem($trashItem);
        }

        return $uiTrashItems;
    }

    /**
     * Provides the number of trash items in the trash.
     *
     * @return int number of trash items in trash
     */
    public function countTrashItems()
    {
        $query = new Query();
        $query->limit = 0; //doing a count only request
        return $this->trashService->findTrashItems($query)->count;
    }

    /**
     * Creates a UiTrashItem using a TrashItem.
     *
     * @param TrashItem $trashItem
     *
     * @return UiTrashItem
     */
    private function createUiTrashItem(TrashItem $trashItem)
    {
        $path = $this->pathService->loadPathLocations($trashItem);
        $contentType = $this->contentTypeService->loadContentType(
            $trashItem->getContentInfo()->contentTypeId
        );

        return new UiTrashItem($trashItem, ['locationPath' => $path, 'contentType' => $contentType]);
    }

    /**
     * Empties the trash.
     */
    public function emptyTrash()
    {
        $this->trashService->emptyTrash();
    }

    /**
     * Restores TrashItems to their original location.
     * If $parentLocationId is provided, items are restored there instead of its original location.
     *
     * @param array $trashItemIds
     * @param int|null (optional) $parentLocationId if provided where items are restored
     */
    public function restoreTrashItems(array $trashItemIds, $parentLocationId = null)
    {
        foreach ($trashItemIds as $trashItemId) {
            $this->restoreTrashItem($trashItemId, $parentLocationId);
        }
    }

    /**
     * Restore a TrashItem
     * If $parentLocationId is provided, item is restored there instead of its original location.
     *
     * @param $trashItemId
     * @param int|null (optional) $parentLocationId if provided where the item is restored
     */
    private function restoreTrashItem($trashItemId, $parentLocationId = null)
    {
        $trashItem = $this->trashService->loadTrashItem($trashItemId);

        if ($parentLocationId === null) {
            $this->trashService->recover($trashItem);
        } else {
            $parentLocation = $this->locationService->loadLocation($parentLocationId);
            $this->trashService->recover($trashItem, $parentLocation);
        }
    }
}
