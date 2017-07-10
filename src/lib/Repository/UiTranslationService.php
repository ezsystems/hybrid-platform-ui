<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\LanguageService;
use eZ\Publish\API\Repository\Values\Content\VersionInfo;

/**
 * Service for loading translations.
 */
class UiTranslationService
{
    /**
     * @var LanguageService
     */
    private $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
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
        return array_map(function ($languageCode) {
            return $this->languageService->loadLanguage($languageCode);
        }, $versionInfo->languageCodes);
    }
}
