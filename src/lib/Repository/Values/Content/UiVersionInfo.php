<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository\Values\Content;

use eZ\Publish\Core\Repository\Values\Content\VersionInfo;
use eZ\Publish\API\Repository\Values\Content\VersionInfo as APIVersionInfo;

/**
 * Extends original value object in order to provide additional fields.
 * Takes a standard VersionInfo instance and retrieves properties from it in addition to the provided properties.
 *
 * @property-read \eZ\Publish\API\Repository\Values\User\User $author
 * @property-read \eZ\Publish\API\Repository\Values\Content\Language[] $translations
 */
class UiVersionInfo extends VersionInfo
{
    /**
     * The author of the version.
     *
     * @var \eZ\Publish\API\Repository\Values\User\User
     */
    protected $author;

    /**
     * Translations for the version.
     *
     * @var \eZ\Publish\API\Repository\Values\Content\Language[]
     */
    protected $translations;

    public function __construct(APIVersionInfo $versionInfo, array $properties = [])
    {
        parent::__construct(get_object_vars($versionInfo) + $properties);
    }
}
