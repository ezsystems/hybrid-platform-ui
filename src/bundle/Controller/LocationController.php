<?php

/**
 * File containing the LocationController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use eZ\Publish\Core\MVC\Symfony\Controller\Controller;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use EzSystems\HybridPlatformUi\Form\UiFormFactory;
use EzSystems\HybridPlatformUi\Repository\UiLocationService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class LocationController extends Controller
{
    /**
     * @var UiLocationService
     */
    private $uiLocationService;

    /**
     * @var UiFormFactory
     */
    private $formFactory;

    public function __construct(UiLocationService $uiLocationService, UiFormFactory $formFactory)
    {
        $this->uiLocationService = $uiLocationService;
        $this->formFactory = $formFactory;
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
        Request $request,
        RouterInterface $router
    ) {
        $actionsForm = $this->formFactory->createLocationsActionForm();
        $actionsForm->handleRequest($request);

        if ($actionsForm->isValid()) {
            $locationIds = array_keys($actionsForm->get('removeLocations')->getData());

            if ($actionsForm->get('delete')->isClicked()) {
                $this->uiLocationService->deleteLocations($locationIds);
            }
        }

        return new RedirectResponse(
            $router->generate(
                '_ez_content_view',
                [
                    'contentId' => $content->id,
                    'locationId' => $request->query->get('redirectLocationId', null),
                ]
            )
        );
    }

    public function swapLocationAction(
        $contentId,
        $locationId,
        Request $request,
        RouterInterface $router
    ) {
        $swapLocationsForm = $this->formFactory->createLocationsContentSwapForm();
        $swapLocationsForm->handleRequest($request);

        if ($swapLocationsForm->isValid()) {
            $newLocationId = $swapLocationsForm->get('new_location_id')->getData();
            $this->uiLocationService->swapLocations($locationId, $newLocationId);
        }
        //@TODO Show success/fail message to user
        return new RedirectResponse(
            $router->generate(
                '_ez_content_view',
                ['contentId' => $contentId]
            )
        );
    }
}
