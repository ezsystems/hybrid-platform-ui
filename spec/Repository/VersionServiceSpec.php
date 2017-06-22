<?php

namespace spec\EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\VersionInfo;
use EzSystems\HybridPlatformUi\Repository\VersionService;
use PhpSpec\ObjectBehavior;

class VersionServiceSpec extends ObjectBehavior
{
    function let(ContentService $contentService)
    {
        $this->beConstructedWith($contentService);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(VersionService::class);
    }

    function it_should_delete_versions(
        ContentService $contentService,
        ContentInfo $contentInfo,
        VersionInfo $versionInfo
    ) {
        $contentId = '1';
        $versionNumber = 10;

        $contentService->loadContentInfo($contentId)->willReturn($contentInfo)->shouldBeCalled();
        $contentService->loadVersionInfo($contentInfo, $versionNumber)->willReturn($versionInfo)->shouldBeCalled();
        $contentService->deleteVersion($versionInfo)->shouldBeCalled();

        $this->deleteVersion($contentId, $versionNumber);
    }
}
