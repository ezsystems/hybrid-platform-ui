<?php
/**
 * File containing the VersionController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use eZ\Publish\API\Repository\Values\Content\Content;
use EzSystems\HybridPlatformUi\Form\UiFormFactory;
use EzSystems\HybridPlatformUi\Repository\UiVersionService;
use Symfony\Component\HttpFoundation\Request;

class VersionController extends TabController
{
    public function draftActionsAction(
        Content $content,
        Request $request,
        UiVersionService $versionService,
        UiFormFactory $formFactory
    ) {
        $draftActionsForm = $formFactory->createVersionsDraftActionForm();
        $draftActionsForm->handleRequest($request);

        $redirectLocationId = $request->query->get('redirectLocationId', $content->contentInfo->mainLocationId);

        if ($draftActionsForm->isValid()) {
            $selectedIds = $draftActionsForm->get('versionIds')->getData();

            if ($draftActionsForm->get('delete')->isClicked()) {
                foreach (array_keys($selectedIds) as $versionId) {
                    $versionService->deleteVersion((int) $content->id, $versionId);
                }
            }
        }
        //@TODO Show success/fail message to user
        return $this->reloadTab('versions', $content->id, $redirectLocationId);
    }
}
