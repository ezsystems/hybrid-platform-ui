<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Mapper\Form;

/**
 * Maps location information to expected formats.
 */
class LocationMapper
{
    /**
     * Map locations and content to data required in form.
     *
     * @param eZ\Publish\API\Repository\Values\Content\Location[] $locations
     *
     * @return array
     */
    public function mapToForm(array $locations)
    {
        $data = [
            'removeLocations' => [],
            'locationVisibility' => [],
        ];

        foreach ($locations as $location) {
            $data['removeLocations'][$location->id] = false;
            $data['locationVisibility'][$location->id] = !$location->hidden;
        }

        return $data;
    }
}
