<?php

namespace spec\EzSystems\HybridPlatformUi\Mapper\Form;

use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\Core\Repository\Values\Content\VersionInfo;
use EzSystems\HybridPlatformUi\Mapper\Form\VersionMapper;
use PhpSpec\ObjectBehavior;

class VersionMapperSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(VersionMapper::class);
    }

    function it_should_map_versions_to_form()
    {
        $contentId = 1;
        $versionNumber = 12;
        $contentInfo = new ContentInfo(['id' => $contentId]);
        $versionInfo = new VersionInfo(['versionNo' => $versionNumber]);

        $versions = [$versionInfo];

        $expectedFormValues = ['contentId' => $contentId, 'versionIds' => [$versionNumber => false]];

        $this->mapToForm($versions, $contentInfo)->shouldBeLike($expectedFormValues);
    }
}
