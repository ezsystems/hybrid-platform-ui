<?php

namespace spec\EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\LanguageService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\Language;
use eZ\Publish\Core\Repository\Values\Content\VersionInfo;
use EzSystems\HybridPlatformUi\Repository\Permission\UiPermissionResolver;
use EzSystems\HybridPlatformUi\Repository\Values\Content\UiLanguage;
use PhpSpec\ObjectBehavior;

class UiTranslationServiceSpec extends ObjectBehavior
{
    function let(
        LanguageService $languageService,
        ContentService $contentService,
        UiPermissionResolver $permissionResolver
    ) {
        $this->beConstructedWith($languageService, $contentService, $permissionResolver);
    }

    function it_should_load_all_translations_for_a_piece_of_content(
        LanguageService $languageService,
        ContentInfo $contentInfo
    ) {
        $languageCode1 = 'eng-GB';
        $languageCode2 = 'eng-US';

        $versionInfo = new VersionInfo([
            'languageCodes' => [
                $languageCode1,
                $languageCode2,
            ],
            'contentInfo' => $contentInfo,
        ]);

        $language1 = new Language(['languageCode' => $languageCode1]);
        $language2 = new Language(['languageCode' => $languageCode2]);

        $languageService->loadLanguage($languageCode1)->willReturn($language1)->shouldBeCalled();
        $languageService->loadLanguage($languageCode2)->willReturn($language2)->shouldBeCalled();

        $uiLanguage1 = new UiLanguage($language1);
        $uiLanguage2 = new UiLanguage($language2);

        $this->loadTranslations($versionInfo)->shouldBeLike([$uiLanguage1, $uiLanguage2]);
    }

    function it_loads_ui_translations_with_a_main_flag(LanguageService $languageService)
    {
        $languageCode = 'eng-GB';

        $contentInfo = new ContentInfo(['mainLanguageCode' => $languageCode]);

        $versionInfo = new VersionInfo([
            'languageCodes' => [
                $languageCode,
            ],
            'contentInfo' => $contentInfo,
        ]);

        $language = new Language(['languageCode' => $languageCode]);

        $languageService->loadLanguage($languageCode)->willReturn($language)->shouldBeCalled();

        $uiLanguage = new UiLanguage($language, ['main' => true]);

        $this->loadTranslations($versionInfo)->shouldBeLike([$uiLanguage]);
    }

    public function it_deletes_translations(ContentService $contentService)
    {
        $languageCode1 = 'eng-GB';
        $languageCode2 = 'eng-US';
        $languageCodes = [$languageCode1, $languageCode2];

        $contentInfo = new ContentInfo();

        $versionInfo = new VersionInfo([
            'contentInfo' => $contentInfo,
        ]);

        $contentService->removeTranslation($contentInfo, $languageCode1)->shouldBeCalled();
        $contentService->removeTranslation($contentInfo, $languageCode2)->shouldBeCalled();

        $this->deleteTranslations($languageCodes, $versionInfo);
    }
}
