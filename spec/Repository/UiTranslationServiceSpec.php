<?php

namespace spec\EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\LanguageService;
use eZ\Publish\API\Repository\Values\Content\Language;
use eZ\Publish\Core\Repository\Values\Content\VersionInfo;
use PhpSpec\ObjectBehavior;

class UiTranslationServiceSpec extends ObjectBehavior
{
    function let(LanguageService $languageService)
    {
        $this->beConstructedWith($languageService);
    }

    function it_should_load_all_translations_for_a_piece_of_content(
        LanguageService $languageService,
        Language $language1,
        Language $language2
    ) {
        $languageCode1 = 'eng-GB';
        $languageCode2 = 'eng-US';

        $versionInfo = new VersionInfo(['languageCodes' => [$languageCode1, $languageCode2]]);

        $languageService->loadLanguage($languageCode1)->willReturn($language1)->shouldBeCalled();
        $languageService->loadLanguage($languageCode2)->willReturn($language2)->shouldBeCalled();

        $this->loadTranslations($versionInfo)->shouldBe([$language1, $language2]);
    }
}
