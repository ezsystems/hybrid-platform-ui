<?php

namespace EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\SearchService;
use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Ancestor;
use eZ\Publish\API\Repository\Values\Content\Search\SearchHit;
use eZ\Publish\Core\Repository\Values\Content\Location;

class PathService
{
    /**
     * @var SearchService
     */
    private $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function loadPathLocations(Location $location)
    {
        $locationQuery = new LocationQuery([
            'filter' => new Ancestor($location->pathString)
        ]);

        return array_map(function (SearchHit $searchHit) {
            return $searchHit->valueObject;
        }, ($this->searchService->findLocations($locationQuery))->searchHits);
    }
}
