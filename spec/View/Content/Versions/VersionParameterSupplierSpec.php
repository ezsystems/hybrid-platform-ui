<?php

namespace spec\EzSystems\HybridPlatformUi\View\Content\Versions;

use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use eZ\Publish\Core\Repository\Values\Content\Content;
use eZ\Publish\Core\Repository\Values\Content\VersionInfo;
use EzSystems\HybridPlatformUi\Filter\VersionFilter;
use EzSystems\HybridPlatformUi\Repository\Permission\UiPermissionResolver;
use EzSystems\HybridPlatformUi\Repository\UiVersionService;
use EzSystems\HybridPlatformUi\Repository\Values\Content\UiVersionInfo;
use EzSystems\HybridPlatformUi\View\Content\ParameterSupplier;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class VersionParameterSupplierSpec extends ObjectBehavior
{
    function let(UiVersionService $versionService, VersionFilter $versionFilter, UiPermissionResolver $permissionResolver)
    {
        $this->beConstructedWith($versionService, $versionFilter, $permissionResolver);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ParameterSupplier::class);
    }

    function it_should_supply_us_with_a_list_of_versions_when_user_has_permission(
        UiVersionService $versionService,
        VersionFilter $versionFilter,
        ContentView $contentView,
        UiPermissionResolver $permissionResolver,
        UiVersionInfo $uiVersionInfo
    ) {
        $contentInfo = new ContentInfo();
        $versionInfo = new VersionInfo(['contentInfo' => $contentInfo]);
        $content = new Content(['versionInfo' => $versionInfo]);

        $expectedVersions = [$uiVersionInfo];

        $contentView->getContent()->willReturn($content);
        $permissionResolver->canReadVersion($contentInfo)->willReturn(true);
        $versionService->loadVersions($contentInfo)->willReturn($expectedVersions);
        $versionFilter->filterDrafts($expectedVersions)->willReturn($expectedVersions);
        $versionFilter->filterArchived($expectedVersions)->willReturn($expectedVersions);
        $versionFilter->filterPublished($expectedVersions)->willReturn($expectedVersions);

        $contentView->addParameters([
            'draftVersions' => $expectedVersions,
            'publishedVersions' => $expectedVersions,
            'archivedVersions' => $expectedVersions,
        ])->shouldBeCalled();

        $this->supply($contentView);
    }

    function it_should_not_supply_us_with_a_list_of_versions_when_user_does_not_have_permission(
        UiVersionService $versionService,
        ContentView $contentView,
        UiPermissionResolver $permissionResolver
    ) {
        $contentInfo = new ContentInfo();
        $versionInfo = new VersionInfo(['contentInfo' => $contentInfo]);
        $content = new Content(['versionInfo' => $versionInfo]);

        $contentView->getContent()->willReturn($content);
        $permissionResolver->canReadVersion($contentInfo)->willReturn(false);
        $versionService->loadVersions(Argument::any())->shouldNotBeCalled();
        $contentView->addParameters(Argument::any())->shouldNotBeCalled();

        $this->supply($contentView);
    }
}
