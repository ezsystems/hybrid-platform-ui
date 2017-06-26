<?php
/**
 * File containing the VersionController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use EzSystems\HybridPlatformUi\Repository\VersionService;
use EzSystems\HybridPlatformUiBundle\Form\Versions\DraftActions;
use Symfony\Component\HttpFoundation\Request;

class VersionController extends Controller
{
    public function draftActionsAction(Request $request, VersionService $versionService)
    {
        $draftActionsForm = $this->createForm(DraftActions::class);
        $draftActionsForm->handleRequest($request);

        if ($draftActionsForm->isValid()) {
            $selectedIds = $draftActionsForm->get('versionIds')->getData();
            $contentId = (int) $draftActionsForm->get('contentId')->getData();

            if ($draftActionsForm->get('delete')->isClicked()) {
                foreach (array_keys($selectedIds) as $versionId) {
                    $versionService->deleteVersion($contentId, $versionId);
                }
            }
        }
        //@TODO Show success/fail message to user
        return $this->redirectToRoute('ez_hybrid_platform_ui_dashboard');
    }
}
