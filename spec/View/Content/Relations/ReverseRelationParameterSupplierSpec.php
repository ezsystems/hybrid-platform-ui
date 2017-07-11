<?php

namespace spec\EzSystems\HybridPlatformUi\View\Content\Relations;

use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use eZ\Publish\Core\Repository\Values\Content\Content;
use eZ\Publish\Core\Repository\Values\Content\VersionInfo;
use EzSystems\HybridPlatformUi\Repository\Permission\UiPermissionResolver;
use EzSystems\HybridPlatformUi\Repository\UiRelationService;
use EzSystems\HybridPlatformUi\Repository\Values\Content\UiRelation;
use EzSystems\HybridPlatformUi\View\Content\ParameterSupplier;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReverseRelationParameterSupplierSpec extends ObjectBehavior
{
    function let(UiRelationService $relationService, UiPermissionResolver $permissionResolver)
    {
        $this->beConstructedWith($relationService, $permissionResolver);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ParameterSupplier::class);
    }

    function it_should_supply_us_with_a_list_of_relations_when_user_has_permission(
        UiRelationService $relationService,
        ContentView $contentView,
        UiPermissionResolver $permissionResolver,
        UiRelation $relation
    ) {
        $permissionResolver->canAccessReverseRelations()->willReturn(true);

        $contentInfo = new ContentInfo();
        $versionInfo = new VersionInfo(['contentInfo' => $contentInfo]);
        $content = new Content(['versionInfo' => $versionInfo]);
        $contentView->getContent()->willReturn($content);

        $relationService->loadReverseRelations($contentInfo)->willReturn([$relation]);

        $contentView->addParameters(['reverseRelations' => [$relation]])->shouldBeCalled();

        $this->supply($contentView);
    }

    function it_should_not_supply_us_with_a_list_of_relations_when_user_does_not_have_permission(
        UiRelationService $relationService,
        ContentView $contentView,
        UiPermissionResolver $permissionResolver
    ) {
        $permissionResolver->canAccessReverseRelations()->willReturn(false);

        $relationService->loadReverseRelations(Argument::any())->shouldNotBeCalled();

        $contentView->addParameters(Argument::any())->shouldNotBeCalled();

        $this->supply($contentView);
    }
}
