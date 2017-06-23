<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository\Values\Content;

use eZ\Publish\Core\Repository\Values\Content\Location;

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
     * @var Location[]
     */
    protected $pathLocations;

    /**
     * Main flag.
     *
     * @var bool
     */
    protected $main;

    public function __construct(array $properties = [], Location $location)
    {
        parent::__construct(get_object_vars($location) + $properties);
    }
}
