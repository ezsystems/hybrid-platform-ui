<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Filter;

use eZ\Publish\API\Repository\Values\Content\VersionInfo;

class VersionFilter
{
    /**
     * Filters a list of versions to retrieve only the draft versions.
     *
     * @param VersionInfo[] $versions
     *
     * @return VersionInfo[]
     */
    public function filterDrafts(array $versions)
    {
        return $this->filterVersions($versions, function (VersionInfo $version) {
            return $version->isDraft();
        });
    }

    /**
     * Filters a list of versions to retrieve only the published versions.
     *
     * @param VersionInfo[] $versions
     *
     * @return VersionInfo[]
     */
    public function filterPublished(array $versions)
    {
        return $this->filterVersions($versions, function (VersionInfo $version) {
            return $version->isPublished();
        });
    }

    /**
     * Filters a list of versions to retrieve only the archived versions.
     *
     * @param VersionInfo[] $versions
     *
     * @return VersionInfo[]
     */
    public function filterArchived(array $versions)
    {
        return $this->filterVersions($versions, function (VersionInfo $version) {
            return $version->isArchived();
        });
    }

    private function filterVersions(array $versions, callable $functionUsedToFilter)
    {
        return array_values(array_filter($versions, $functionUsedToFilter));
    }
}
