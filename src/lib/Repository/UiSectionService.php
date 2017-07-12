<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\SectionService;

/**
 * Service for loading sections.
 */
class UiSectionService
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var SectionService
     */
    private $sectionService;

    public function __construct(Repository $repository, SectionService $sectionService)
    {
        $this->repository = $repository;
        $this->sectionService = $sectionService;
    }

    /**
     * Load a section ignoring permissions.
     *
     * @param mixed $sectionId
     * @return \eZ\Publish\API\Repository\Values\Content\Section
     */
    public function loadSection($sectionId)
    {
        return $this->repository->sudo(function () use ($sectionId) {
            return $this->sectionService->loadSection($sectionId);
        });
    }
}
