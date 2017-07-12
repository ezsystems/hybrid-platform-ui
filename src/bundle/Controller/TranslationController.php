<?php

/**
 * File containing the TranslationController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use EzSystems\HybridPlatformUi\Form\UiFormFactory;
use EzSystems\HybridPlatformUi\Repository\UiTranslationService;
use EzSystems\HybridPlatformUi\View\Content\Translations\TranslationParameterSupplier;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class TranslationController extends TabController
{
    /**
     * @var UiTranslationService
     */
    private $uiTranslationService;

    /**
     * @var UiFormFactory
     */
    private $formFactory;

    public function __construct(
        UiTranslationService $uiTranslationService,
        UiFormFactory $formFactory,
        RouterInterface $router,
        ContentService $contentService
    ) {
        $this->uiTranslationService = $uiTranslationService;
        $this->formFactory = $formFactory;
        parent::__construct($router, $contentService);
    }

    public function contentViewTabAction(ContentView $view, TranslationParameterSupplier $translationParameterSupplier)
    {
        $translationParameterSupplier->supply($view);

        $actionsForm = $this->formFactory->createTranslationsActionForm(
            $view->hasParameter('translations') ? $view->getParameter('translations') : []
        );

        $view->addParameters([
            'actionsForm' => $actionsForm->createView(),
        ]);

        return $view;
    }

    public function actionsAction(
        Content $content,
        Request $request
    ) {
        $actionsForm = $this->formFactory->createTranslationsActionForm();
        $actionsForm->handleRequest($request);

        if ($actionsForm->isValid()) {
            $this->deleteTranslationsBasedOnFormSubmit($actionsForm, $content);
        }

        $redirectLocationId = $request->query->get('redirectLocationId', $content->contentInfo->mainLocationId);

        return $this->reloadTab('translations', $content->id, $redirectLocationId);
    }

    private function deleteTranslationsBasedOnFormSubmit(FormInterface $form, Content $content)
    {
        $languageCodes = array_keys($form->get('removeTranslations')->getData());

        if ($form->get('delete')->isClicked()) {
            $this->uiTranslationService->deleteTranslations($languageCodes, $content->getVersionInfo());
        }
    }
}
