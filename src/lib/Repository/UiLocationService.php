<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Repository;
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
     * @var Repository
     */
    private $repository;

    public function __construct(
        LocationService $locationService,
        PathService $pathService,
        Repository $repository
    ) {
        $this->locationService = $locationService;
        $this->pathService = $pathService;
        $this->repository = $repository;
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

        $uiLocations = $this->buildUiLocations($locations);
        $uiLocations = $this->prioritizeMainLocation($uiLocations);

        return $uiLocations;
    }

    /**
     * Deletes secondary locations.
     * Throws exception if there is an attempt to delete the main location.
     *
     * @param array $locationIds
     *
     * @throws ForbiddenException
     */
    public function deleteSecondaryLocations(array $locationIds)
    {
        foreach ($locationIds as $locationId) {
            $location = $this->locationService->loadLocation($locationId);
            $uiLocation = new UiLocation($location);

            if ($uiLocation->isMain()) {
                throw new ForbiddenException('Main location cannot be deleted.');
            }

            $this->locationService->deleteLocation($location);
        }
    }

    private function buildUiLocations(array $locations)
    {
        return array_map(
            function (Location $location) {
                $properties = [
                    'childCount' => $this->locationService->getLocationChildCount($location),
                    'pathLocations' => $this->pathService->loadPathLocations($location),
                    'userCanManage' => $this->repository->canUser('content', 'manage_locations', $location->getContentInfo()),
                    'userCanRemove' => $this->repository->canUser('content', 'remove', $location->getContentInfo(), $location),
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
            if ($location->isMain()) {
                unset($locations[$key]);
                array_unshift($locations, $location);
            }
        }

        return $locations;
    }
}
