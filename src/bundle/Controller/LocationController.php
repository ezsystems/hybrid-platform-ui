<?php

/**
 * File containing the LocationController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\Core\MVC\Symfony\Controller\Controller;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use EzSystems\HybridPlatformUi\Builder\NotificationBuilderFactory;
use EzSystems\HybridPlatformUi\Form\UiFormFactory;
use EzSystems\HybridPlatformUi\Repository\UiLocationService;
use EzSystems\HybridPlatformUi\Response\NotificationResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

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
            $visibilityForm = $formFactory->createLocationVisibilityForm();

            $view->addParameters([
                'locations' => $locations,
                'actionsForm' => $actionsForm->createView(),
                'visibilityForm' => $visibilityForm->createView(),
            ]);
        }

        return $view;
    }

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
                $uiLocationService->deleteLocations($locationIds);
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

    public function visibilityAction(
        Request $request,
        LocationService $locationService,
        UiFormFactory $formFactory,
        TranslatorInterface $translator,
        NotificationBuilderFactory $notificationBuilderFactory
    ) {
        try {
            $visibilityForm = $formFactory->createLocationVisibilityForm();
            $visibilityForm->handleRequest($request);

            if (!$visibilityForm->isValid()) {
                throw new \Exception('Invalid form submission.');
            }

            $visibility = $visibilityForm->get('visibility')->getData();
            $locationId = $visibilityForm->get('locationId')->getData();

            $location = $locationService->loadLocation($locationId);

            if ($visibility) {
                $locationService->unhideLocation($location);
                /** @Desc("The Location #%id% is now visible") */
                $message = $translator->trans(
                    'locationview.locations.notification.visible', ['%id%' => $location->id], 'locationview'
                );

                return $this->success($message, $notificationBuilderFactory);
            }

            $locationService->hideLocation($location);
            /** @Desc("The Location #%id% is now hidden") */
            $message = $translator->trans(
                'locationview.locations.notification.hidden', ['%id%' => $location->id], 'locationview'
            );

            return $this->success($message, $notificationBuilderFactory);
        } catch (\Exception $e) {
            /** @Desc("Error updating location visibility") */
            $message = $translator->trans('locationview.locations.visibility.error', [], 'locationview');

            return $this->error($message, $e->getMessage(), $notificationBuilderFactory);
        }
    }

    private function success(string $message, NotificationBuilderFactory $notificationBuilderFactory)
    {
        $notification = $notificationBuilderFactory
            ->create()
            ->setMessage($message)
            ->setSuccess()
            ->getResult();

        return NotificationResponse::success($notification);
    }

    private function error(string $message, string $errorDetails, NotificationBuilderFactory $notificationBuilderFactory)
    {
        $notification = $notificationBuilderFactory
            ->create()
            ->setMessage($message)
            ->setError()
            ->setErrorDetails($errorDetails)
            ->getResult();

        return NotificationResponse::error($notification);
    }
}
