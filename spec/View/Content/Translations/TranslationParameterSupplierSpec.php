<?php

namespace spec\EzSystems\HybridPlatformUi\View\Content\Relations;

use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\VersionInfo;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use EzSystems\HybridPlatformUi\Repository\UiTranslationService;
use EzSystems\HybridPlatformUi\Repository\Values\Content\UiLanguage;
use PhpSpec\ObjectBehavior;

class TranslationParameterSupplierSpec extends ObjectBehavior
{
    function let(UiTranslationService $translationService)
    {
        $this->beConstructedWith($translationService);
    }

    function it_should_supply_us_with_a_list_of_translations(
        UiTranslationService $translationService,
        ContentView $contentView,
        UiLanguage $uiLanguage
    ) {
        $versionInfo = new VersionInfo();
        $content = new Content(['versionInfo' => $versionInfo]);
        $contentView->getContent()->willReturn($content);

        $translationService->loadTranslations($versionInfo)->willReturn([$uiLanguage]);

        $contentView->addParameters(['translations' => [$uiLanguage]])->shouldBeCalled();

        $this->supply($contentView);
    }
}
