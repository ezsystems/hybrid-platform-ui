<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\ContentService;
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
     * @var ContentService
     */
    private $contentService;

    /**
     * @var PathService
     */
    private $pathService;

    public function __construct(
        LocationService $locationService,
        ContentService $contentService,
        PathService $pathService
    ) {
        $this->locationService = $locationService;
        $this->pathService = $pathService;
        $this->contentService = $contentService;
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
     * Deletes locations.
     * Throws exception if there is an attempt to delete the main location.
     *
     * @param array $locationIds
     * @param int $contentId
     *
     * @throws ForbiddenException
     */
    public function deleteLocations(array $locationIds, int $contentId)
    {
        $contentInfo = $this->contentService->loadContentInfo($contentId);

        foreach ($locationIds as $locationId) {
            $location = $this->locationService->loadLocation($locationId);

            if ($this->isMainLocation($location, $contentInfo)) {
                throw new ForbiddenException('Main location cannot be deleted.');
            }

            $this->locationService->deleteLocation($location);
        }
    }

    private function buildUiLocations(array $locations, ContentInfo $contentInfo)
    {
        return array_map(
            function (Location $location) use ($contentInfo) {
                $properties = [
                    'childCount' => $this->locationService->getLocationChildCount($location),
                    'pathLocations' => $this->pathService->loadPathLocations($location),
                    'main' => $this->isMainLocation($location, $contentInfo),
                ];

                $uiLocation = new UiLocation($location, $properties);

                return $uiLocation;
            },
            $locations
        );
    }

    private function isMainLocation(Location $location, ContentInfo $contentInfo)
    {
        return $location->id === $contentInfo->mainLocationId;
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
