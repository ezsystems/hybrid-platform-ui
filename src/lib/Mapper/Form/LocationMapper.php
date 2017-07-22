<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Mapper\Form;

use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\Core\Base\Exceptions\UnauthorizedException;

/**
 * Maps location information to expected formats.
 */
class LocationMapper
{
    /**
     * @var \eZ\Publish\API\Repository\PermissionResolver
     */
    private $permissionResolver;

    public function __construct(Repository $repository)
    {
        $this->permissionResolver = $repository->getPermissionResolver();
    }

    /**
     * Map locations and content to data required in form.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Location[] $locations
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
            $data['canRemoveLocations'][$location->id] = $this->canRemoveLocation($location);
        }

        return $data;
    }

    private function canRemoveLocation(Location $location)
    {
        return $this->permissionResolver->canUser('content', 'manage_locations', $location->getContentInfo())
            && $this->permissionResolver->canUser('content', 'remove', $location->getContentInfo(), [$location]);
    }
}
