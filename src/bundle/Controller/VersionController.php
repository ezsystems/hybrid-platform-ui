<?php
/**
 * File containing the VersionController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use eZ\Publish\Core\MVC\Symfony\Controller\Controller;
use EzSystems\HybridPlatformUi\Form\UiFormFactory;
use EzSystems\HybridPlatformUi\Repository\UiVersionService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class VersionController extends Controller
{
    public function draftActionsAction(
        $contentId,
        Request $request,
        UiVersionService $versionService,
        UiFormFactory $formFactory,
        RouterInterface $router
    ) {
        $draftActionsForm = $formFactory->createVersionsDraftActionForm();
        $draftActionsForm->handleRequest($request);

        if ($draftActionsForm->isValid()) {
            $selectedIds = $draftActionsForm->get('versionIds')->getData();

            if ($draftActionsForm->get('delete')->isClicked()) {
                foreach (array_keys($selectedIds) as $versionId) {
                    $versionService->deleteVersion((int) $contentId, $versionId);
                }
            }
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
