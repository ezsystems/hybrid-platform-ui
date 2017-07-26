<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository\Permission;

use eZ\Publish\API\Repository\PermissionResolver;
use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\API\Repository\Values\Content\VersionInfo;

/**
 * This class is a proxy to allow handling of permissions needed for the HybridPlatformUiBundle in one place.
 */
class UiPermissionResolver
{
    const CONTENT_MODULE = 'content';
    const REVERSE_RELATION = 'reverserelatedlist';
    const MANAGE_LOCATIONS = 'manage_locations';
    const REMOVE = 'remove';
    const DELETE = 'delete';
    const READ_VERSION = 'versionread';
    const REMOVE_VERSION = 'versionremove';
    const EDIT_CONTENT = 'edit';

    /**
     * @var PermissionResolver
     */
    private $permissionResolver;

    public function __construct(
        Repository $repository
    ) {
        $this->permissionResolver = $repository->getPermissionResolver();
    }

    /**
     * Checks if user can access reverse relations.
     *
     * @return bool
     */
    public function canAccessReverseRelations()
    {
        return $this->permissionResolver->hasAccess(self::CONTENT_MODULE, self::REVERSE_RELATION) === true;
    }

    /**
     * Checks if the current user can manage locations.
     *
     * @param ContentInfo $contentInfo
     *
     * @return bool
     */
    public function canManageLocations(ContentInfo $contentInfo)
    {
        return $this->permissionResolver->canUser(
            self::CONTENT_MODULE, self::MANAGE_LOCATIONS, $contentInfo
        );
    }

    /**
     * Checks if the current user is allowed to remove content.
     *
     * @param ContentInfo $contentInfo
     * @param Location $targetLocation
     *
     * @return bool
     */
    public function canRemoveContent(ContentInfo $contentInfo, Location $targetLocation)
    {
        return $this->permissionResolver->canUser(
            self::CONTENT_MODULE, self::REMOVE, $contentInfo, [$targetLocation]
        );
    }

    /**
     * Checks if the current user is allowed to remove translation.
     *
     * @param VersionInfo $versionInfo
     *
     * @return bool
     */
    public function canRemoveTranslation(VersionInfo $versionInfo)
    {
        return $this->permissionResolver->canUser(
            self::CONTENT_MODULE, self::DELETE, $versionInfo
        );
    }

    /**
     * Checks if the current user can read the version.
     *
     * @param ContentInfo $contentInfo
     *
     * @return bool
     */
    public function canReadVersion(ContentInfo $contentInfo)
    {
        return $this->permissionResolver->canUser(self::CONTENT_MODULE, self::READ_VERSION, $contentInfo);
    }

    public function canRemoveVersion(VersionInfo $versionInfo)
    {
        return $this->permissionResolver->canUser(self::CONTENT_MODULE, self::REMOVE_VERSION, $versionInfo);
    }

    public function canEditVersion(VersionInfo $versionInfo)
    {
        return $this->permissionResolver->canUser(self::CONTENT_MODULE, self::EDIT_CONTENT, $versionInfo->getContentInfo());
    }
}
