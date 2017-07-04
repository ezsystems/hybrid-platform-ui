<?php

/**
 * File containing the LocationController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use EzSystems\HybridPlatformUi\Form\UiFormFactory;
use EzSystems\HybridPlatformUi\Repository\UiLocationService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class LocationController
{
    public function swapLocationAction(
        $contentId,
        $locationId,
        Request $request,
        UiFormFactory $formFactory,
        UiLocationService $uiLocationService,
        RouterInterface $router
    ) {
        $swapLocationsForm = $formFactory->createLocationsContentSwapForm();
        $swapLocationsForm->handleRequest($request);

        if ($swapLocationsForm->isValid()) {
            $newLocationId = $swapLocationsForm->get('new_location_id')->getData();
            $uiLocationService->swapLocations($locationId, $newLocationId);
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
