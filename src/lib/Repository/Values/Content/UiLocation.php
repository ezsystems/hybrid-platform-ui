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
     * Path locations.
     *
     * @var \eZ\Publish\API\Repository\Values\Content\Location[]
     */
    protected $pathLocations;

    public function __construct(APILocation $location, array $properties = [])
    {
        parent::__construct(get_object_vars($location) + $properties);
    }

    /**
     * Is main location.
     *
     * @return bool
     */
    public function isMain()
    {
        return $this->id === $this->getContentInfo()->mainLocationId;
    }
}
