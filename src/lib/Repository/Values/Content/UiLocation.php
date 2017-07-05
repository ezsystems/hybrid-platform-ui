<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository\Values\Content;

use eZ\Publish\Core\Repository\Values\Content\Location;
use eZ\Publish\API\Repository\Values\Content\Location as APILocation;

/**
 * Extends original value object in order to provide additional fields.
 * Takes a standard location instance and retrieves properties from it in addition to the provided properties.
 */
class UiLocation extends Location
{
    /**
     * Child count.
     *
     * @var int
     */
    protected $childCount;

    /**
     * Is main location.
     *
     * @var bool
     */
    protected $main;

    /**
     * Path locations.
     *
     * @var \eZ\Publish\API\Repository\Values\Content\Location[]
     */
    protected $pathLocations;

    /**
     * User can manage.
     *
     * @var bool
     */
    protected $userCanManage;

    /**
     * User can remove.
     *
     * @var bool
     */
    protected $userCanRemove;

    public function __construct(APILocation $location, array $properties = [])
    {
        parent::__construct(get_object_vars($location) + $properties);
    }

    /**
     * Can delete location.
     *
     * @return bool
     */
    public function canDelete()
    {
        return $this->userCanManage && $this->userCanRemove;
    }
}
