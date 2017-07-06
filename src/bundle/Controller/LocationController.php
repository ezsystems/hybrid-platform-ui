<?php

/**
 * File containing the LocationController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use EzSystems\HybridPlatformUi\Form\UiFormFactory;
use EzSystems\HybridPlatformUi\Repository\UiLocationService;
use Symfony\Component\HttpFoundation\Request;

class LocationController extends TabController
{
    public function contentViewTabAction(
        ContentView $view,
        UiLocationService $uiLocationService,
        UiFormFactory $formFactory
    ) {
        $versionInfo = $view->getContent()->getVersionInfo();
        $contentInfo = $versionInfo->getContentInfo();

        if ($contentInfo->published) {
            $locations = $uiLocationService->loadLocations($contentInfo);
            $actionsForm = $formFactory->createLocationsActionForm($locations);

            $view->addParameters([
                'locations' => $locations,
                'actionsForm' => $actionsForm->createView(),
            ]);
        }

        return $view;
    }

    public function actionsAction(
        Content $content,
        Request $request,
        UiLocationService $uiLocationService,
        UiFormFactory $formFactory
    ) {
        $actionsForm = $formFactory->createLocationsActionForm();
        $actionsForm->handleRequest($request);

        $redirectLocationId = $request->query->get('redirectLocationId', $content->contentInfo->mainLocationId);

        if ($actionsForm->isValid()) {
            $locationIds = array_keys($actionsForm->get('removeLocations')->getData());

            if ($actionsForm->get('delete')->isClicked()) {
                $uiLocationService->deleteLocations($locationIds);

                if (in_array($redirectLocationId, $locationIds)) {
                    return $this->resetLocation($content->id);
                }
            }

            if ($actionsForm->get('add')->isClicked()) {
                $uiLocationService->addLocation(
                    $content->contentInfo,
                    $actionsForm->get('parentLocationId')->getData()
                );
            }
        }

        return $this->reloadTab('locations', $content->id, $redirectLocationId);
    }
}
