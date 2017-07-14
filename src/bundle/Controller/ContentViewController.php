<?php

/**
 * File containing the ContentViewController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use EzSystems\HybridPlatformUi\Form\UiFormFactory;
use EzSystems\HybridPlatformUi\Repository\UiFieldGroupService;
use EzSystems\HybridPlatformUi\Repository\UiSectionService;
use EzSystems\HybridPlatformUi\Repository\UiTranslationService;
use EzSystems\HybridPlatformUi\Repository\UiUserService;
use EzSystems\HybridPlatformUi\View\Content\Relations\RelationParameterSupplier;
use EzSystems\HybridPlatformUi\View\Content\Relations\ReverseRelationParameterSupplier;
use Symfony\Component\HttpFoundation\Request;

class ContentViewController extends TabController
{
    public function contentTabAction(ContentView $view, UiFieldGroupService $fieldGroupService)
    {
        $versionInfo = $view->getContent()->getVersionInfo();

        $view->addParameters([
            'fieldGroups' => $fieldGroupService->loadFieldGroups($versionInfo->getContentInfo()),
        ]);

        return $view;
    }

    public function detailsTabAction(
        ContentView $view,
        UiUserService $userService,
        UiTranslationService $translationService,
        UiSectionService $sectionService,
        UiFormFactory $formFactory
    ) {
        $versionInfo = $view->getContent()->getVersionInfo();
        $contentInfo = $versionInfo->getContentInfo();

        $orderingForm = $formFactory->createLocationOrderingForm($view->getLocation());

        $view->addParameters([
            'section' => $sectionService->loadSection($contentInfo->sectionId),
            'contentInfo' => $contentInfo,
            'versionInfo' => $versionInfo,
            'creator' => $userService->findUserById($contentInfo->ownerId),
            'lastContributor' => $userService->findUserById($versionInfo->creatorId),
            'translations' => $translationService->loadTranslations($versionInfo),
            'orderingForm' => $orderingForm->createView(),
        ]);

        return $view;
    }

    public function relationsTabAction(
        ContentView $view,
        RelationParameterSupplier $relationParameterSupplier,
        ReverseRelationParameterSupplier $reverseRelationParameterSupplier,
        Request $request
    ) {
        $relationParameterSupplier->supply($view);
        $reverseRelationParameterSupplier->supply($view);

        //@TODO improve how the supplies work with setting the current page
        $view->getParameter('relations')->setCurrentPage($request->query->getInt('relationPage', 1));
        $view->getParameter('reverseRelations')->setCurrentPage($request->query->getInt('reverseRelationPage', 1));

        return $view;
    }

    public function translationsTabAction(ContentView $view, UiTranslationService $translationService)
    {
        $view->addParameters([
            'translations' => $translationService->loadTranslations($view->getContent()->getVersionInfo()),
        ]);

        return $view;
    }
}
