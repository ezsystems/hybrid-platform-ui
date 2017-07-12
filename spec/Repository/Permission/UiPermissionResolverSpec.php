<?php

namespace spec\EzSystems\HybridPlatformUi\Repository\Permission;

use eZ\Publish\API\Repository\PermissionResolver;
use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\API\Repository\Values\Content\VersionInfo;
use EzSystems\HybridPlatformUi\Repository\Permission\UiPermissionResolver;
use PhpSpec\ObjectBehavior;

class UiPermissionResolverSpec extends ObjectBehavior
{
    function let(
        Repository $repository,
        PermissionResolver $permissionResolver
    ) {
        $this->beConstructedWith($repository);
        $repository->getPermissionResolver()->willReturn($permissionResolver);
    }

    function it_checks_user_can_view_reverse_relations(PermissionResolver $permissionResolver)
    {
        $permissionResolver->hasAccess(UiPermissionResolver::CONTENT_MODULE, UiPermissionResolver::REVERSE_RELATION)->willReturn(true);

        $this->canAccessReverseRelations()->shouldBe(true);
    }

    function it_checks_user_cannot_view_reverse_relations(PermissionResolver $permissionResolver)
    {
        $permissionResolver->hasAccess(UiPermissionResolver::CONTENT_MODULE, UiPermissionResolver::REVERSE_RELATION)->willReturn(false);

        $this->canAccessReverseRelations()->shouldBe(false);
    }

    function it_checks_user_can_manage_locations(PermissionResolver $permissionResolver, ContentInfo $contentInfo)
    {
        $permissionResolver->canUser(UiPermissionResolver::CONTENT_MODULE, UiPermissionResolver::MANAGE_LOCATIONS, $contentInfo)->willReturn(true);

        $this->canManageLocations($contentInfo)->shouldBe(true);
    }

    function it_checks_user_cannot_manage_locations(PermissionResolver $permissionResolver, ContentInfo $contentInfo)
    {
        $permissionResolver->canUser(UiPermissionResolver::CONTENT_MODULE, UiPermissionResolver::MANAGE_LOCATIONS, $contentInfo)->willReturn(false);

        $this->canManageLocations($contentInfo)->shouldBe(false);
    }

    function it_checks_user_can_remove_content(
        PermissionResolver $permissionResolver,
        ContentInfo $contentInfo,
        Location $location
    ) {
        $permissionResolver->canUser(UiPermissionResolver::CONTENT_MODULE, UiPermissionResolver::REMOVE, $contentInfo, [$location])->willReturn(true);

        $this->canRemoveContent($contentInfo, $location)->shouldBe(true);
    }

    function it_checks_user_cannot_remove_content(
        PermissionResolver $permissionResolver,
        ContentInfo $contentInfo,
        Location $location
    ) {
        $permissionResolver->canUser(UiPermissionResolver::CONTENT_MODULE, UiPermissionResolver::REMOVE, $contentInfo, [$location])->willReturn(false);

        $this->canRemoveContent($contentInfo, $location)->shouldBe(false);
    }

    function it_checks_user_can_remove_translation(
        PermissionResolver $permissionResolver,
        VersionInfo $versionInfo
    ) {
        $permissionResolver->canUser(UiPermissionResolver::CONTENT_MODULE, UiPermissionResolver::DELETE, $versionInfo)->willReturn(true);

        $this->canRemoveTranslation($versionInfo)->shouldBe(true);
    }

    function it_checks_user_cannot_remove_translation(
        PermissionResolver $permissionResolver,
        VersionInfo $versionInfo
    ) {
        $permissionResolver->canUser(UiPermissionResolver::CONTENT_MODULE, UiPermissionResolver::DELETE, $versionInfo)->willReturn(false);

        $this->canRemoveTranslation($versionInfo)->shouldBe(false);
    }
}
