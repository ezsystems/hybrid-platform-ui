<?php

namespace EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\Location;
use EzSystems\HybridPlatformUi\Decorator\LocationDecorator;

class DecoratedLocationRepository
{
    /**
     * @var LocationService
     */
    private $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    public function loadLocations(ContentInfo $contentInfo)
    {
        $locationService = $this->locationService;
        $locations = $locationService->loadLocations($contentInfo);

        return array_map(function (Location $location) use ($locationService) {
            $decoratedLocation = new LocationDecorator($location);
            $decoratedLocation->childCount = $locationService->getLocationChildCount(
                $decoratedLocation->getValueObject()
            );

            return $decoratedLocation;
        }, $locations);
    }
}
