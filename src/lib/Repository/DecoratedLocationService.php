<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\Location;
use EzSystems\HybridPlatformUi\Decorator\LocationDecorator;

/**
 * Service for loading locations with additional data not provided by the original API.
 * Returns decorated location objects to facilitate storing the additional data without modifying the original object.
 */
class DecoratedLocationService
{
    /**
     * @var LocationService
     */
    private $locationService;

    /**
     * @var PathService
     */
    private $pathService;

    public function __construct(LocationService $locationService, PathService $pathService)
    {
        $this->locationService = $locationService;
        $this->pathService = $pathService;
    }

    /**
     * Load decorated locations.
     *
     * @param ContentInfo $contentInfo
     *
     * @return LocationDecorator[]
     */
    public function loadLocations(ContentInfo $contentInfo)
    {
        $locationService = $this->locationService;
        $pathService = $this->pathService;
        $locations = $locationService->loadLocations($contentInfo);

        return array_map(function (Location $location) use ($locationService, $pathService) {
            $decoratedLocation = new LocationDecorator($location);

            $decoratedLocation->childCount = $locationService->getLocationChildCount(
                $decoratedLocation->getValueObject()
            );

            $decoratedLocation->pathLocations = $pathService->loadPathLocations(
                $decoratedLocation->getValueObject()
            );

            return $decoratedLocation;
        }, $locations);
    }
}
