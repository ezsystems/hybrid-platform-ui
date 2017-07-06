<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository\Permission;

use eZ\Publish\API\Repository\PermissionResolver;
use eZ\Publish\API\Repository\Repository;

/**
 * This class is a proxy to allow handling of permissions needed for the HybridPlatformUiBundle in one place.
 */
class UiPermissionResolver
{
    const CONTENT_MODULE = 'content';
    const REVERSE_RELATION = 'reverserelatedlist';

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
    public function canAccessReverseRelated()
    {
        return $this->permissionResolver->hasAccess(self::CONTENT_MODULE, self::REVERSE_RELATION) === true;
    }
}
