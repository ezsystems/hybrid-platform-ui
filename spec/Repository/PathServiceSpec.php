<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace spec\EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\SearchService;
use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Search\SearchHit;
use eZ\Publish\API\Repository\Values\Content\Search\SearchResult;
use eZ\Publish\Core\Repository\Values\Content\Location;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PathServiceSpec extends ObjectBehavior
{
    function let(SearchService $searchService)
    {
        $this->beConstructedWith($searchService);
    }

    function it_loads_path_locations(SearchService $searchService)
    {
        $location = new Location([
            'pathString' => '/2/3/',
        ]);

        $searchHit = new SearchHit(['valueObject' => $location]);

        $searchResults = new SearchResult([
            'searchHits' => [
                $searchHit,
                $searchHit,
            ],
        ]);

        $searchService->findLocations(Argument::type(LocationQuery::class))->willReturn($searchResults);

        $this->loadPathLocations($location)->shouldBeLike([$location, $location]);
    }
}
