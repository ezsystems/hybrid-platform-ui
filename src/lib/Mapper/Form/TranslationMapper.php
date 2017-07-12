<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Mapper\Form;

class TranslationMapper
{
    /**
     * Map locations and content to data required in form.
     *
     * @param eZ\Publish\API\Repository\Values\Content\Language[] $translations)
     *
     * @return array
     */
    public function mapToForm(array $translations)
    {
        $data = [
            'removeTranslations' => [],
        ];

        foreach ($translations as $translation) {
            $data['removeTranslations'][$translation->languageCode] = false;
        }

        return $data;
    }
}
