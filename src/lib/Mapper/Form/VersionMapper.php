<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Mapper\Form;

use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\VersionInfo;

/**
 * Maps version information to expected formats.
 */
class VersionMapper
{
    /**
     * Map versions and content to data required in form.
     *
     * @param VersionInfo[] $versions
     * @param ContentInfo $contentInfo
     *
     * @return array
     */
    public function mapToForm(array $versions, ContentInfo $contentInfo)
    {
        $data = ['versionIds' => [], 'contentId' => $contentInfo->id];
        foreach ($versions as $version) {
            $data['versionIds'][$version->versionNo] = false;
        }

        return $data;
    }
}
