<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\VersionInfo;
use EzSystems\HybridPlatformUi\Repository\Permission\UiPermissionResolver;
use EzSystems\HybridPlatformUi\Repository\Values\Content\UiVersionInfo;

/**
 * Service for allowing deletion of versions.
 */
class UiVersionService
{
    /**
     * @var ContentService
     */
    private $contentService;

    /**
     * @var UiUserService
     */
    private $uiUserService;

    /**
     * @var UiTranslationService
     */
    private $uiTranslationService;

    /**
     * @var UiPermissionResolver
     */
    private $permissionResolver;

    public function __construct(
        ContentService $contentService,
        UiUserService $uiUserService,
        UiTranslationService $uiTranslationService,
        UiPermissionResolver $permissionResolver
    ) {
        $this->contentService = $contentService;
        $this->uiUserService = $uiUserService;
        $this->uiTranslationService = $uiTranslationService;
        $this->permissionResolver = $permissionResolver;
    }

    /**
     * Load versions and adds the author and translations.
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     *
     * @param ContentInfo $contentInfo
     *
     * @return UiVersionInfo[]
     */
    public function loadVersions(ContentInfo $contentInfo)
    {
        return $this->buildUiVersions(
            $this->contentService->loadVersions($contentInfo)
        );
    }

    /**
     * Deletes a version based on the contentInfo and versionNo.
     *
     * @param ContentInfo $contentInfo
     * @param int $versionNo
     */
    public function deleteVersion(ContentInfo $contentInfo, int $versionNo)
    {
        $this->contentService->deleteVersion(
            $this->loadVersionInfo($contentInfo, $versionNo)
        );
    }

    /**
     * Creates a new draft based on the contentInfo and versionNo.
     *
     * @param ContentInfo $contentInfo
     * @param mixed $versionNo
     */
    public function createDraft(ContentInfo $contentInfo, $versionNo)
    {
        return $this->contentService->createContentDraft(
            $contentInfo,
            $this->loadVersionInfo($contentInfo, $versionNo)
        );
    }

    private function buildUiVersions(array $versions)
    {
        return array_map(
            function (VersionInfo $versionInfo) {
                $properties = [
                    'author' => $this->uiUserService->findUserById($versionInfo->creatorId),
                    'translations' => $this->uiTranslationService->loadTranslations($versionInfo),
                    'canEdit' => $versionInfo->status === VersionInfo::STATUS_DRAFT
                        && $this->permissionResolver->canEditVersion($versionInfo),
                ];

                return new UiVersionInfo($versionInfo, $properties);
            },
            $versions
        );
    }

    private function loadVersionInfo(ContentInfo $contentInfo, $versionNo)
    {
        return $this->contentService->loadVersionInfo($contentInfo, $versionNo);
    }
}
