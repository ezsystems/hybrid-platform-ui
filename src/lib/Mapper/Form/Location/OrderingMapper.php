<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Mapper\Form\Location;

use eZ\Publish\API\Repository\Values\Content\Location;

/**
 * Maps location ordering information to expected format.
 */
class OrderingMapper
{
    public function mapToForm(Location $location)
    {
        return [
            'sortField' => $location->sortField,
            'sortOrder' => $location->sortOrder,
        ];
    }
}
