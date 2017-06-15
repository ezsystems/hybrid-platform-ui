<?php

/**
 * File containing the ContentViewController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\HybridPlatformUiBundle\Controller;

use eZ\Publish\Core\MVC\Symfony\Controller\Controller;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;

class ContentViewController extends Controller
{
    public function versionsTabAction(ContentView $view)
    {
        $contentInfo = $view->getContent()->getVersionInfo()->getContentInfo();
        $contentService = $this->getRepository()->getContentService();
        $versions = $contentService->loadVersions($contentInfo);

        $userService = $this->getRepository()->getUserService();
        $authors = [];
        foreach ($versions as $version) {
            $authors[$version->id] = $userService->loadUser($version->creatorId);
        }

        $versionFilter = $this->container->get('ezsystems.platform_ui.hybrid.filter.version_filter');

        $view->addParameters([
            'draftVersions' => $versionFilter->filterDrafts($versions),
            'publishedVersions' => $versionFilter->filterPublished($versions),
            'archivedVersions' => $versionFilter->filterArchived($versions),
            'authors' => $authors
        ]);

        return $view;
    }
}
