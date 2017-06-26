<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Decorator;

use eZ\Publish\API\Repository\Values\Content\Location;

/**
 * Decorates original value object in order to provide additional fields that can be dynamically set.
 * Proxies public location value object methods to the original object.
 */
class LocationDecorator extends ValueObjectDecorator
{
    /**
     * Externally calculated and set child count.
     *
     * @var int
     */
    public $childCount;

    /**
     * Externally loaded and set path locations.
     *
     * @var Location[]
     */
    public $pathLocations;

    /**
     * Externally set main flag.
     *
     * @var bool
     */
    public $main;

    /**
     * @var Location
     */
    protected $location;

    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    /**
     * Get value object.
     *
     * @return Location
     */
    public function getValueObject()
    {
        return $this->location;
    }

    public function isDraft()
    {
        return $this->location->isDraft();
    }

    public function getSortClauses()
    {
        return $this->location->getSortClauses();
    }

    public function getContentInfo()
    {
        return $this->location->getContentInfo();
    }
}
