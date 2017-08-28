<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use eZ\Publish\API\Repository\Values\Content\Location;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EzSystems\HybridPlatformUi\Form\UiFormFactory;
use EzSystems\HybridPlatformUi\Repository\UiLocationService;

class ActionBarController extends Controller
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
        UiFormFactory $formFactory
    ) {
        $this->uiLocationService = $uiLocationService;
        $this->formFactory = $formFactory;
    }

    public function trashLocationAction(
        Location $location,
        Request $request
    ) {
        $redirectLocation = $location;

        $trashLocationForm = $this->formFactory->createLocationContentTrashForm();
        $trashLocationForm->handleRequest($request);

        if ($trashLocationForm->isValid()) {
            $redirectLocation = $this->uiLocationService->trashLocationAndReturnParent($location);
        }

        return $this->redirectToRoute($redirectLocation);
    }

    public function moveLocationAction(
        Location $location,
        Request $request
    ) {
        $moveLocationForm = $this->formFactory->createLocationContentMoveForm();
        $moveLocationForm->handleRequest($request);

        if ($moveLocationForm->isValid()) {
            $newParentLocationId = $moveLocationForm->get('newParentLocationId')->getData();
            $location = $this->uiLocationService->moveLocation($location, $newParentLocationId);
        }

        return $this->redirectToRoute($location);
    }
}
