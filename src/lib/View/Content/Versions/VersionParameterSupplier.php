<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\View\Content\Versions;

use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use EzSystems\HybridPlatformUi\Filter\VersionFilter;
use EzSystems\HybridPlatformUi\Repository\Permission\UiPermissionResolver;
use EzSystems\HybridPlatformUi\Repository\UiVersionService;
use EzSystems\HybridPlatformUi\View\Content\ParameterSupplier;

/**
 * Adds parameters needed to display the versions.
 */
class VersionParameterSupplier implements ParameterSupplier
{
    /**
     * @var UiVersionService
     */
    private $versionService;

    /**
     * @var VersionFilter
     */
    private $versionFilter;

    /**
     * @var UiPermissionResolver
     */
    private $permissionResolver;

    public function __construct(
        UiVersionService $versionService,
        VersionFilter $versionFilter,
        UiPermissionResolver $permissionResolver
    ) {
        $this->versionService = $versionService;
        $this->versionFilter = $versionFilter;
        $this->permissionResolver = $permissionResolver;
    }

    /**
     * Adds versions to the view if user has the correct permissions.
     *
     * {@inheritdoc}
     */
    public function supply(ContentView $contentView)
    {
        $contentInfo = $contentView->getContent()->contentInfo;

        if (!$this->permissionResolver->canReadVersion($contentInfo)) {
            return;
        }

        $versions = $this->versionService->loadVersions($contentInfo);

        $contentView->addParameters([
            'draftVersions' => $this->versionFilter->filterDrafts($versions),
            'publishedVersions' => $this->versionFilter->filterPublished($versions),
            'archivedVersions' => $this->versionFilter->filterArchived($versions),
        ]);
    }
}
