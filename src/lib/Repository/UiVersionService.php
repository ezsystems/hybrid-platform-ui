<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\VersionInfo;
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

    public function __construct(
        ContentService $contentService,
        UiUserService $uiUserService,
        UiTranslationService $uiTranslationService
    ) {
        $this->contentService = $contentService;
        $this->uiUserService = $uiUserService;
        $this->uiTranslationService = $uiTranslationService;
    }

    /**
     * Load versions and adds the author and translations.
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
     * Deletes a version based on the contentId and versionNo.
     *
     * @param int $contentId
     * @param int $versionNo
     */
    public function deleteVersion(int $contentId, int $versionNo)
    {
        $versionInfo = $this->loadVersionInfo(
            $this->loadContentInfo($contentId),
            $versionNo
        );

        $this->contentService->deleteVersion($versionInfo);
    }

    /**
     * Creates a new draft based on the content and versionNo.
     *
     * @param mixed $contentId
     * @param mixed $versionNo
     */
    public function createDraft($contentId, $versionNo)
    {
        $contentInfo = $this->loadContentInfo($contentId);
        $versionInfo = $this->loadVersionInfo(
            $this->loadContentInfo($contentId),
            $versionNo
        );

        $this->contentService->createContentDraft($contentInfo, $versionInfo);
    }

    private function buildUiVersions(array $versions)
    {
        return array_map(
            function (VersionInfo $versionInfo) {
                $properties = [
                    'author' => $this->uiUserService->findUserById($versionInfo->creatorId),
                    'translations' => $this->uiTranslationService->loadTranslations($versionInfo),
                ];

                return new UiVersionInfo($versionInfo, $properties);
            },
            $versions
        );
    }

    private function loadContentInfo($contentId)
    {
        return $this->contentService->loadContentInfo($contentId);
    }

    private function loadVersionInfo(ContentInfo $contentInfo, $versionNo)
    {
        return $this->contentService->loadVersionInfo($contentInfo, $versionNo);
    }
}
