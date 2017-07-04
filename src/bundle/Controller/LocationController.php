<?php

/**
 * File containing the LocationController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use eZ\Publish\API\Repository\Values\Content\Location;
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
        Location $location,
        Request $request,
        UiLocationService $uiLocationService,
        RouterInterface $router,
        UiFormFactory $formFactory
    ) {
        $actionsForm = $formFactory->createLocationsActionForm();
        $actionsForm->handleRequest($request);

        $redirectLocationId = $location->id;

        if ($actionsForm->isValid()) {
            $locationIds = array_keys($actionsForm->get('removeLocations')->getData());

            if ($actionsForm->get('delete')->isClicked()) {
                $uiLocationService->deleteSecondaryLocations($locationIds);

                if (in_array($location->id, $locationIds)) {
                    $redirectLocationId = $location->getContentInfo()->mainLocationId;
                }
            }
        }

        return new RedirectResponse(
            $router->generate(
                '_ez_content_view',
                [
                    'contentId' => $content->id,
                    'locationId' => $redirectLocationId,
                ]
            )
        );
    }
}
