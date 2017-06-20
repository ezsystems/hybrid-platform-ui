<?php

namespace EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\Location;
use EzSystems\HybridPlatformUi\Decorator\LocationDecorator;

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
