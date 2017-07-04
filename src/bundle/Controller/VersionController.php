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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class VersionController extends Controller
{
    /**
     * @var UiVersionService
     */
    private $uiVersionService;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(UiVersionService $uiVersionService, RouterInterface $router)
    {
        $this->uiVersionService = $uiVersionService;
        $this->router = $router;
    }

    public function draftActionsAction(
        $contentId,
        Request $request,
        UiFormFactory $formFactory
    ) {
        $draftActionsForm = $formFactory->createVersionsDraftActionForm();
        $draftActionsForm->handleRequest($request);

        if ($draftActionsForm->isValid()) {
            $this->deleteVersionsBasedOnFormSubmit($draftActionsForm, $contentId);
        }

        return $this->redirectToContentView($contentId);
    }

    public function archiveActionsAction(
        $contentId,
        Request $request,
        UiFormFactory $formFactory
    ) {
        $archiveActionsForm = $formFactory->createVersionsArchivedActionForm();
        $archiveActionsForm->handleRequest($request);

        if ($archiveActionsForm->isValid()) {
            $this->deleteVersionsBasedOnFormSubmit($archiveActionsForm, $contentId);
        }

        return $this->redirectToContentView($contentId);
    }

    private function deleteVersionsBasedOnFormSubmit(FormInterface $form, $contentId)
    {
        $selectedIds = array_keys($form->get('versionIds')->getData());

        if ($form->get('delete')->isClicked()) {
            foreach ($selectedIds as $versionId) {
                $this->uiVersionService->deleteVersion((int) $contentId, $versionId);
            }
        }
    }

    protected function redirectToContentView($contentId)
    {
        //@TODO Show success/fail message to user
        return new RedirectResponse(
            $this->router->generate(
                '_ez_content_view',
                ['contentId' => $contentId]
            )
        );
    }
}
