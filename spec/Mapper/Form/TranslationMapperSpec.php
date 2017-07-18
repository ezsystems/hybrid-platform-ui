<?php

namespace spec\EzSystems\HybridPlatformUi\Mapper\Form;

use eZ\Publish\API\Repository\Values\Content\Language;
use PhpSpec\ObjectBehavior;

class TranslationMapperSpec extends ObjectBehavior
{
    function it_should_map_translations_to_form()
    {
        $firstLanguageCode = 'eng-GB';
        $secondLanguageCode = 'eng-US';

        $locations = [
            new Language(['languageCode' => $firstLanguageCode]),
            new Language(['languageCode' => $secondLanguageCode]),
        ];

        $expectedData = [
            'removeTranslations' => [
                $firstLanguageCode => false,
                $secondLanguageCode => false,
            ],
        ];

        $this->mapToForm($locations)->shouldBeLike($expectedData);
    }
}
