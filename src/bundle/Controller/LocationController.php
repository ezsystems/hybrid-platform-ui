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
use EzSystems\HybridPlatformUi\Form\UiFormFactory;
use EzSystems\HybridPlatformUi\Repository\UiLocationService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class LocationController extends Controller
{
    public function actionsAction(
        Content $content,
        Request $request,
        UiLocationService $uiLocationService,
        RouterInterface $router,
        UiFormFactory $formFactory
    ) {
        $actionsForm = $formFactory->createLocationsActionForm();
        $actionsForm->handleRequest($request);

        if ($actionsForm->isValid()) {
            $locationIds = array_keys($actionsForm->get('removeLocations')->getData());

            if ($actionsForm->get('delete')->isClicked()) {
                $uiLocationService->deleteLocations($locationIds, $content->id);
            }
        }

        return new RedirectResponse(
            $router->generate(
                '_ez_content_view',
                [
                    'contentId' => $content->id,
                    'layout' => 'true',
                    'viewType' => 'full',
                ]
            )
        );
    }
}
