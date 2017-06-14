<?php

namespace EzSystems\HybridPlatformUi\Filter;

use eZ\Publish\API\Repository\Values\Content\VersionInfo;

class VersionFilter
{
    public function filterDrafts(array $versions)
    {
        return array_values(array_filter($versions, function (VersionInfo $version) {
            return $version->isDraft();
        }));
    }

    public function filterPublished(array $versions)
    {
        return array_values(array_filter($versions, function (VersionInfo $version) {
            return $version->isPublished();
        }));
    }

    public function filterArchived(array $versions)
    {
        return array_values(array_filter($versions, function (VersionInfo $version) {
            return $version->isArchived();
        }));
    }
}
