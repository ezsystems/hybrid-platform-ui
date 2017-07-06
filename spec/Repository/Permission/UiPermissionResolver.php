<?php

namespace spec\EzSystems\HybridPlatformUi\Repository\Permission;

use eZ\Publish\API\Repository\PermissionResolver;
use eZ\Publish\API\Repository\Repository;
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

        $this->canAccessReverseRelated()->shouldBe(true);
    }

    function it_checks_user_cannot_view_reverse_relations(PermissionResolver $permissionResolver)
    {
        $permissionResolver->hasAccess(UiPermissionResolver::CONTENT_MODULE, UiPermissionResolver::REVERSE_RELATION)->willReturn(false);

        $this->canAccessReverseRelated()->shouldBe(false);
    }
}
