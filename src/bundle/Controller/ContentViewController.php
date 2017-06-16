<?php

/**
 * File containing the ContentViewController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\HybridPlatformUiBundle\Controller;

use eZ\Publish\API\Repository\Repository;
use eZ\Publish\Core\MVC\Symfony\Controller\Controller;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;

class ContentViewController extends Controller
{
    public function versionsTabAction(ContentView $view)
    {
        $repository = $this->getRepository();
        $contentInfo = $view->getContent()->getVersionInfo()->getContentInfo();
        $contentService = $repository->getContentService();
        $versions = $contentService->loadVersions($contentInfo);

        $versionFilter = $this->container->get('ezsystems.platform_ui.hybrid.filter.version_filter');

        $view->addParameters([
            'draftVersions' => $versionFilter->filterDrafts($versions),
            'publishedVersions' => $versionFilter->filterPublished($versions),
            'archivedVersions' => $versionFilter->filterArchived($versions),
            'authors' => $this->loadUsersForVersions($repository, $versions)
        ]);

        return $view;
    }

    private function loadUsersForVersions(Repository $repository, array $versions)
    {
        $authors = [];
        $userService = $repository->getUserService();
        foreach ($versions as $version) {
            $authors[$version->id] = $repository->sudo(
                function () use ($version, $userService) {
                    return $userService->loadUser($version->creatorId);
                }
            );
        }

        return $authors;
    }
}
