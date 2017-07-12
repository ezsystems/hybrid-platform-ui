<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Mapper\Form\Location;

use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\Location;

/**
 * Maps location ordering information to expected format.
 */
class OrderingMapper
{
    /**
     * @var LocationService
     */
    private $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    public function mapToForm(Location $location)
    {
        $updateStruct = $this->locationService->newLocationUpdateStruct();

        $updateStruct->sortField = $location->sortField;
        $updateStruct->sortOrder = $location->sortOrder;

        return $updateStruct;
    }
}
