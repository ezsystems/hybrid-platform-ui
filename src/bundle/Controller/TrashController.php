<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use EzSystems\HybridPlatformUi\Components\MainContent;
use EzSystems\HybridPlatformUi\Form\UiFormFactory;
use EzSystems\HybridPlatformUi\Repository\UiTrashService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TrashController extends Controller
{
    /** @var UiTrashService */
    public $uiTrashService;

    /** @var UiFormFactory */
    public $formFactory;

    public function __construct(
        UiTrashService $uiTrashService,
        UiFormFactory $formFactory)
    {
        $this->uiTrashService = $uiTrashService;
        $this->formFactory = $formFactory;
    }

    public function viewTrashAction(MainContent $mainContent)
    {
        $uiTrashItems = [];
        $nbTrashItems = $this->uiTrashService->countTrashItems();
        $hasTrashItems = false;

        if ($nbTrashItems > 0) {
            $uiTrashItems = $this->uiTrashService->getTrashItems();
            $hasTrashItems = true;
        }

        $emptyTrashForm = $this->formFactory->createEmptyTrashForm(!$hasTrashItems);

        $restoreTrashItemsForm = $this->formFactory->createRestoreTrashItemsForm(
            $uiTrashItems
        );

        $mainContent->setTemplate('EzSystemsHybridPlatformUiBundle::trash.html.twig');
        $mainContent->setParameters([
            'nbTrashItems' => $nbTrashItems,
            'uiTrashItems' => $uiTrashItems,
            'emptyTrashForm' => $emptyTrashForm->createView(),
            'restoreForm' => $restoreTrashItemsForm->createView(),
        ]);

        return $mainContent;
    }

    public function emptyTrashAction(Request $request)
    {
        $emptyTrashForm = $this->formFactory->createEmptyTrashForm();
        $emptyTrashForm->handleRequest($request);

        if ($emptyTrashForm->isValid()) {
            $this->uiTrashService->emptyTrash();
        }

        return $this->refreshTrash();
    }

    public function restoreTrashItemsAction(Request $request)
    {
        $restoreTrashItemsForm = $this->formFactory->createRestoreTrashItemsForm();
        $restoreTrashItemsForm->handleRequest($request);

        if ($restoreTrashItemsForm->isValid()) {
            $trashItemsIds = array_keys($restoreTrashItemsForm->get('trashItems')->getData());

            if ($restoreTrashItemsForm->get('restore')->isClicked()) {
                $this->uiTrashService->restoreTrashItems($trashItemsIds);
            }

            if ($restoreTrashItemsForm->get('restoreNewParent')->isClicked()) {
                $parentLocationId = $restoreTrashItemsForm->get('newParentLocationId')->getData();
                $this->uiTrashService->restoreTrashItems($trashItemsIds, $parentLocationId);
            }
        }

        return $this->refreshTrash();
    }

    private function refreshTrash()
    {
        return $this->redirectToRoute('ez_hybrid_platform_ui_trash_view');
    }
}
