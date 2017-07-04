<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\Core\Base\Exceptions\ForbiddenException;
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

    /**
     * @var ContentTypeService
     */
    private $contentTypeService;

    public function __construct(
        LocationService $locationService,
        PathService $pathService,
        ContentTypeService $contentTypeService
    ) {
        $this->locationService = $locationService;
        $this->pathService = $pathService;
        $this->contentTypeService = $contentTypeService;
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

        $uiLocations = $this->buildUiLocations($locations, $contentInfo);
        $uiLocations = $this->prioritizeMainLocation($uiLocations);

        return $uiLocations;
    }

    /**
     * Swaps locations.
     *
     * @param int $locationId
     * @param int $newLocationId
     */
    public function swapLocations(int $locationId, int $newLocationId)
    {
        $currentLocation = $this->locationService->loadLocation($locationId);
        $newLocation = $this->locationService->loadLocation($newLocationId);

        $childCount = $this->locationService->getLocationChildCount($currentLocation);
        $contentType = $this->contentTypeService->loadContentType($newLocation->getContentInfo()->contentTypeId);

        if (!$contentType->isContainer && $childCount) {
            throw new ForbiddenException('Cannot swap location that has sub items with a location that is not a container');
        }
        $this->locationService->swapLocation($currentLocation, $newLocation);
    }

    private function buildUiLocations(array $locations, ContentInfo $contentInfo)
    {
        return array_map(
            function (Location $location) use ($contentInfo) {
                $properties = [
                    'childCount' => $this->locationService->getLocationChildCount($location),
                    'pathLocations' => $this->pathService->loadPathLocations($location),
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
