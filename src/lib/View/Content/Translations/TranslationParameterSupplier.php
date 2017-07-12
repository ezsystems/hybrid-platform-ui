<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\View\Content\Translations;

use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use EzSystems\HybridPlatformUi\Repository\UiTranslationService;
use EzSystems\HybridPlatformUi\View\Content\ParameterSupplier;

/**
 * Adds parameters needed to display translations.
 */
class TranslationParameterSupplier implements ParameterSupplier
{
    /**
     * @var UiTranslationService
     */
    private $uiTranslationService;

    public function __construct(UiTranslationService $uiTranslationService)
    {
        $this->uiTranslationService = $uiTranslationService;
    }

    /**
     * Adds translations to the view.
     *
     * {@inheritdoc}
     */
    public function supply(ContentView $contentView)
    {
        $translations = $this->uiTranslationService->loadTranslations($contentView->getContent()->getVersionInfo());

        $contentView->addParameters([
            'translations' => $translations,
        ]);
    }
}
