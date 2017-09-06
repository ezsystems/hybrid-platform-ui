<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository\Values\Content;

use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\Core\Repository\Values\Content\TrashItem;
use eZ\Publish\API\Repository\Values\Content\TrashItem as APITrashItem;
use eZ\Publish\API\Repository\Values\ContentType\ContentType;

/**
 * Class UiTrashItem.
 *
 * Provides information needed to display a TrashItem including the TrashItem itself.
 */
class UiTrashItem extends TrashItem
{
    /** @var Location[] */
    protected $locationPath;

    /** @var ContentType */
    protected $contentType;

    public function __construct(APITrashItem $trashItem, array $properties = [])
    {
        parent::__construct(get_object_vars($trashItem) + $properties);
    }

    /**
     * Tells if the parent has been deleted (or is currently in trash.
     *
     * @return bool `true` if the parent is deleted
     */
    public function isParentDeleted(): bool
    {
        $parentFound = false;

        foreach ($this->locationPath as $location) {
            if ($this->parentLocationId === $location->id) {
                $parentFound = true;
                break;
            }
        }

        return !$parentFound;
    }
}
