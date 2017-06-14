<?php

namespace spec\EzSystems\HybridPlatformUi\Filter;

use eZ\Publish\API\Repository\Values\Content\VersionInfo;
use EzSystems\HybridPlatformUi\Filter\VersionFilter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class VersionFilterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(VersionFilter::class);
    }

    function it_should_filter_drafts(
        VersionInfo $versionInfo1,
        VersionInfo $versionInfo2,
        VersionInfo $versionInfo3
    ) {
        $versions = [
            $versionInfo1,
            $versionInfo2,
            $versionInfo3
        ];

        $versionInfo1->isDraft()->willReturn(true)->shouldBeCalled();
        $versionInfo2->isDraft()->willReturn(false)->shouldBeCalled();
        $versionInfo3->isDraft()->willReturn(true)->shouldBeCalled();

        $this->filterDrafts($versions)->shouldBeLike([$versionInfo1, $versionInfo3]);
    }

    function it_should_filter_published(
        VersionInfo $versionInfo1,
        VersionInfo $versionInfo2,
        VersionInfo $versionInfo3
    ) {
        $versions = [
            $versionInfo1,
            $versionInfo2,
            $versionInfo3
        ];

        $versionInfo1->isPublished()->willReturn(false)->shouldBeCalled();
        $versionInfo2->isPublished()->willReturn(true)->shouldBeCalled();
        $versionInfo3->isPublished()->willReturn(false)->shouldBeCalled();

        $this->filterPublished($versions)->shouldBeLike([$versionInfo2]);
    }

    function it_should_filter_archived(
        VersionInfo $versionInfo1,
        VersionInfo $versionInfo2,
        VersionInfo $versionInfo3
    ) {
        $versions = [
            $versionInfo1,
            $versionInfo2,
            $versionInfo3
        ];

        $versionInfo1->isArchived()->willReturn(false)->shouldBeCalled();
        $versionInfo2->isArchived()->willReturn(true)->shouldBeCalled();
        $versionInfo3->isArchived()->willReturn(true)->shouldBeCalled();

        $this->filterArchived($versions)->shouldBeLike([$versionInfo2, $versionInfo3]);
    }
}
