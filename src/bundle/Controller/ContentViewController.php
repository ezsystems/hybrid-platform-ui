<?php

/**
 * File containing the ContentViewController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use EzSystems\HybridPlatformUi\Form\UiFormFactory;
use EzSystems\HybridPlatformUi\Repository\UiFieldGroupService;
use EzSystems\HybridPlatformUi\Repository\UiLocationService;
use EzSystems\HybridPlatformUi\Repository\UiSectionService;
use EzSystems\HybridPlatformUi\Repository\UiTranslationService;
use EzSystems\HybridPlatformUi\Repository\UiUserService;
use EzSystems\HybridPlatformUi\View\Content\ContentType\ContentTypeParameterSupplier;
use EzSystems\HybridPlatformUi\View\Content\Locations\LocationParameterSupplier;
use EzSystems\HybridPlatformUi\View\Content\Relations\RelationParameterSupplier;
use EzSystems\HybridPlatformUi\View\Content\Relations\ReverseRelationParameterSupplier;

class ContentViewController extends TabController
{
    /**
     * @var UiFormFactory
     */
    private $formFactory;

    /**
     * @var UiLocationService
     */
    private $uiLocationService;

    /**
     * @var LocationService
     */
    private $locationService;

    public function __construct(
        UiFormFactory $formFactory,
        UiLocationService $uiLocationService,
        LocationService $locationService
    ) {
        $this->formFactory = $formFactory;
        $this->uiLocationService = $uiLocationService;
        $this->locationService = $locationService;
    }

    public function locationViewAction(
        ContentView $view,
        ContentTypeParameterSupplier $contentTypeParameterSupplier,
        LocationParameterSupplier $locationParameterSupplier
    ) {
        $contentTypeParameterSupplier->supply($view);
        $locationParameterSupplier->supply($view);
        $location = $view->getLocation();

        $trashDisabled = !$this->uiLocationService->canRemoveLocation($location);

        $trashLocationForm = $this->formFactory->createLocationContentTrashForm(
            $trashDisabled
        );

        $moveDisabled = !$this->uiLocationService->canMoveLocation($location);
        $moveLocationForm = $this->formFactory->createLocationContentMoveForm($moveDisabled);

        $view->addParameters([
            'trashLocationForm' => $trashLocationForm->createView(),
            'moveLocationForm' => $moveLocationForm->createView(),
            'childCount' => $this->locationService->getLocationChildCount($location),
        ]);

        return $view;
    }

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
        ReverseRelationParameterSupplier $reverseRelationParameterSupplier
    ) {
        $relationParameterSupplier->supply($view);
        $reverseRelationParameterSupplier->supply($view);

        return $view;
    }
}
