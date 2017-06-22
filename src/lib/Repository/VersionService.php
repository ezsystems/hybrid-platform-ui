<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\ContentService;

/**
 * Service for allowing deletion of versions.
 */
class VersionService
{
    /**
     * @var ContentService
     */
    private $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    /**
     * Deletes a version based on the contentId and versionNo.
     *
     * @param int $contentId
     * @param int $versionNo
     */
    public function deleteVersion(int $contentId, int $versionNo)
    {
        $versionInfo = $this->contentService->loadVersionInfo(
            $this->contentService->loadContentInfo($contentId),
            $versionNo
        );

        $this->contentService->deleteVersion($versionInfo);
    }
}
