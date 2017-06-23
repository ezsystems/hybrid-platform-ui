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
     * Loads all locations for $contentInfo with each location wrapped by a decorator.
     * Additionally retrieves and sets child count, path locations and whether a location is main.
     * Intended usage is admin only, where we display information that isn't provided by the standard location service.
     *
     * @param ContentInfo $contentInfo
     *
     * @return LocationDecorator[]
     */
    public function loadLocations(ContentInfo $contentInfo)
    {
        $locations = $this->locationService->loadLocations($contentInfo);

        $decoratedLocations = $this->decorateLocations($locations, $contentInfo);
        $decoratedLocations = $this->prioritizeMainLocation($decoratedLocations);

        return $decoratedLocations;
    }

    private function decorateLocations(array $locations, ContentInfo $contentInfo)
    {
        $locationService = $this->locationService;
        $pathService = $this->pathService;

        return array_map(
            function (Location $location) use ($locationService, $pathService, $contentInfo) {
                $decoratedLocation = new LocationDecorator($location);

                $decoratedLocation->childCount = $locationService->getLocationChildCount(
                    $location
                );

                $decoratedLocation->pathLocations = $pathService->loadPathLocations(
                    $location
                );

                $decoratedLocation->main = ($location->id === $contentInfo->mainLocationId);

                return $decoratedLocation;
            },
            $locations
        );
    }

    private function prioritizeMainLocation(array $decoratedLocations)
    {
        foreach ($decoratedLocations as $key => $decoratedLocation) {
            if ($decoratedLocation->main) {
                unset($decoratedLocations[$key]);
                array_unshift($decoratedLocations, $decoratedLocation);
            }
        }

        return $decoratedLocations;
    }
}
