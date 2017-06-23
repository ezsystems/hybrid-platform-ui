<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\Location;
use EzSystems\HybridPlatformUi\Repository\Values\Content\UiLocation;

/**
 * Service for loading locations with additional data not provided by the original API.
 * Returns ui location objects which inherit from location and provide additional properties.
 */
class UiLocationService
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
     * Loads ui locations for $contentInfo.
     * Retrieves and sets child count, path locations and whether location is main on each ui location.
     * Intended usage is admin only, where we display information that isn't provided by the standard location service.
     *
     * @param ContentInfo $contentInfo
     *
     * @return UiLocation[]
     */
    public function loadLocations(ContentInfo $contentInfo)
    {
        $locations = $this->locationService->loadLocations($contentInfo);

        $uiLocations = $this->makeUiLocations($locations, $contentInfo);
        $uiLocations = $this->prioritizeMainLocation($uiLocations);

        return $uiLocations;
    }

    private function makeUiLocations(array $locations, ContentInfo $contentInfo)
    {
        $locationService = $this->locationService;
        $pathService = $this->pathService;

        return array_map(
            function (Location $location) use ($locationService, $pathService, $contentInfo) {
                $properties = [
                    'childCount' => $locationService->getLocationChildCount($location),
                    'pathLocations' => $pathService->loadPathLocations($location),
                    'main' => ($location->id === $contentInfo->mainLocationId),
                ];

                $uiLocation = new UiLocation($location, $properties);

                return $uiLocation;
            },
            $locations
        );
    }

    private function prioritizeMainLocation(array $locations)
    {
        foreach ($locations as $key => $location) {
            if ($location->main) {
                unset($locations[$key]);
                array_unshift($locations, $location);
            }
        }

        return $locations;
    }
}
