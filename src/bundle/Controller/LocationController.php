<?php

/**
 * File containing the LocationController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use eZ\Publish\API\Repository\Values\Content\Location;
use EzSystems\HybridPlatformUi\Form\UiFormFactory;
use EzSystems\HybridPlatformUi\Repository\UiLocationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class LocationController extends TabController
{
    /**
     * @var UiLocationService
     */
    private $uiLocationService;

    /**
     * @var UiFormFactory
     */
    private $formFactory;

    public function __construct(
        UiLocationService $uiLocationService,
        UiFormFactory $formFactory,
        ContentService $contentService,
        RouterInterface $router
    ) {
        $this->uiLocationService = $uiLocationService;
        $this->formFactory = $formFactory;
        parent::__construct($router, $contentService);
    }

    public function contentViewTabAction(
        ContentView $view
    ) {
        $versionInfo = $view->getContent()->getVersionInfo();
        $contentInfo = $versionInfo->getContentInfo();

        if ($contentInfo->published) {
            $locations = $this->uiLocationService->loadLocations($contentInfo);
            $actionsForm = $this->formFactory->createLocationsActionForm($locations);
            $swapLocationsForm = $this->formFactory->createLocationsContentSwapForm();

            $view->addParameters([
                'locations' => $locations,
                'actionsForm' => $actionsForm->createView(),
                'swapLocationsForm' => $swapLocationsForm->createView(),
            ]);
        }

        return $view;
    }

    public function actionsAction(
        Content $content,
        Request $request
    ) {
        $actionsForm = $this->formFactory->createLocationsActionForm();
        $actionsForm->handleRequest($request);

        $redirectLocationId = $request->query->get('redirectLocationId', $content->contentInfo->mainLocationId);

        if ($actionsForm->isValid()) {
            $locationIds = array_keys($actionsForm->get('removeLocations')->getData());

            if ($actionsForm->get('delete')->isClicked()) {
                $this->uiLocationService->deleteLocations($locationIds);

                if (in_array($redirectLocationId, $locationIds)) {
                    return $this->resetLocation($content->id);
                }
            }
        }

        return $this->reloadTab('locations', $content->id, $redirectLocationId);
    }

    public function swapLocationAction(
        Content $content,
        Location $location,
        Request $request
    ) {
        $swapLocationsForm = $this->formFactory->createLocationsContentSwapForm();
        $swapLocationsForm->handleRequest($request);

        if ($swapLocationsForm->isValid()) {
            $newLocationId = $swapLocationsForm->get('new_location_id')->getData();
            $this->uiLocationService->swapLocations($location->id, $newLocationId);
        }

        $redirectLocationId = $request->query->get('redirectLocationId', $content->contentInfo->mainLocationId);

        return $this->reloadTab('locations', $content->id, $redirectLocationId);
    }
}
