<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Mapper\Form;

use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\Values\Content\VersionInfo;

class TranslationMapper
{
    /**
     * @var \eZ\Publish\Core\Repository\Permission\PermissionResolver
     */
    private $permissionResolver;

    public function __construct(Repository $repository)
    {
        $this->permissionResolver = $repository->getPermissionResolver();
    }
    /**
     * Map locations and content to data required in form.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\VersionInfo $versionInfo
     * @param \eZ\Publish\API\Repository\Values\Content\Language[] $translations )
     *
     * @return array
     */
    public function mapToForm(VersionInfo $versionInfo, array $translations)
    {
        $data = [
            'removeTranslations' => [],
        ];

        $canRemoveTranslation = $this->permissionResolver->canUser(
            'content', 'delete', $versionInfo
        );

        foreach ($translations as $translation) {
            $data['removeTranslations'][$translation->languageCode] = false;
            $data['canRemoveTranslations'][$translation->languageCode] = $canRemoveTranslation;
        }

        return $data;
    }
}
