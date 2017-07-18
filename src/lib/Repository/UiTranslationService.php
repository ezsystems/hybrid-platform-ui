<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\LanguageService;
use eZ\Publish\API\Repository\Values\Content\Language;
use eZ\Publish\API\Repository\Values\Content\VersionInfo;
use EzSystems\HybridPlatformUi\Repository\Permission\UiPermissionResolver;
use EzSystems\HybridPlatformUi\Repository\Values\Content\UiLanguage;

/**
 * Service for loading translations.
 */
class UiTranslationService
{
    /**
     * @var LanguageService
     */
    private $languageService;

    /**
     * @var ContentService
     */
    private $contentService;

    /**
     * @var UiPermissionResolver
     */
    private $permissionResolver;

    public function __construct(
        LanguageService $languageService,
        ContentService $contentService,
        UiPermissionResolver $permissionResolver
    ) {
        $this->languageService = $languageService;
        $this->contentService = $contentService;
        $this->permissionResolver = $permissionResolver;
    }

    /**
     * Load translations for a version.
     *
     * @param VersionInfo $versionInfo
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Language[]
     */
    public function loadTranslations(VersionInfo $versionInfo)
    {
        return array_map(function ($languageCode) use ($versionInfo) {
            return $this->buildUiLanguage(
                $this->languageService->loadLanguage($languageCode),
                $versionInfo
            );
        }, $versionInfo->languageCodes);
    }

    /**
     * Deletes translations.
     *
     * @param array $languageCodes
     */
    public function deleteTranslations(array $languageCodes, VersionInfo $versionInfo)
    {
        foreach ($languageCodes as $languageCode) {
            $this->contentService->removeTranslation($versionInfo->getContentInfo(), $languageCode);
        }
    }

    private function isMainLanguage($languageCode, VersionInfo $versionInfo)
    {
        return $languageCode === $versionInfo->getContentInfo()->mainLanguageCode;
    }

    private function buildUiLanguage(Language $language, VersionInfo $versionInfo)
    {
        return new UiLanguage(
            $language,
            [
                'userCanRemove' => $this->permissionResolver->canRemoveTranslation($versionInfo),
                'main' => $this->isMainLanguage($language->languageCode, $versionInfo),
            ]
        );
    }
}
