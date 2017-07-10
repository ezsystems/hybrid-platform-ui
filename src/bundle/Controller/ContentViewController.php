<?php

/**
 * File containing the ContentViewController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use EzSystems\HybridPlatformUi\Form\UiFormFactory;
use EzSystems\HybridPlatformUi\Repository\UiFieldGroupService;
use EzSystems\HybridPlatformUi\Repository\UiRelationService;
use EzSystems\HybridPlatformUi\Repository\UiTranslationService;
use EzSystems\HybridPlatformUi\Repository\UiUserService;

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
        UiFormFactory $formFactory
    ) {
        $versionInfo = $view->getContent()->getVersionInfo();
        $contentInfo = $versionInfo->getContentInfo();

        $sectionService = $this->getRepository()->getSectionService();
        $section = $sectionService->loadSection($contentInfo->sectionId);

        $orderingForm = $formFactory->createLocationOrderingForm($view->getLocation());

        $view->addParameters([
            'section' => $section,
            'contentInfo' => $contentInfo,
            'versionInfo' => $versionInfo,
            'creator' => $userService->findUserById($contentInfo->ownerId),
            'lastContributor' => $userService->findUserById($versionInfo->creatorId),
            'translations' => $translationService->loadTranslations($versionInfo),
            'orderingForm' => $orderingForm->createView(),
        ]);

        return $view;
    }

    public function relationsTabAction(ContentView $view, UiRelationService $relationService)
    {
        $versionInfo = $view->getContent()->getVersionInfo();
        $contentInfo = $versionInfo->getContentInfo();

        $view->addParameters([
            'relations' => $relationService->loadRelations($versionInfo),
            'reverseRelations' => $relationService->loadReverseRelations($contentInfo),
        ]);

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
