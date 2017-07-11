<?php

namespace spec\EzSystems\HybridPlatformUi\View\Content\Relations;

use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use eZ\Publish\Core\Repository\Values\Content\Content;
use eZ\Publish\Core\Repository\Values\Content\VersionInfo;
use EzSystems\HybridPlatformUi\Repository\UiRelationService;
use EzSystems\HybridPlatformUi\Repository\Values\Content\UiRelation;
use EzSystems\HybridPlatformUi\View\Content\ParameterSupplier;
use PhpSpec\ObjectBehavior;

class RelationParameterSupplierSpec extends ObjectBehavior
{
    function let(UiRelationService $relationService)
    {
        $this->beConstructedWith($relationService);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ParameterSupplier::class);
    }

    function it_should_supply_us_with_a_list_of_relations(
        UiRelationService $relationService,
        ContentView $contentView,
        UiRelation $relation
    ) {
        $versionInfo = new VersionInfo();
        $content = new Content(['versionInfo' => $versionInfo]);
        $contentView->getContent()->willReturn($content);

        $relationService->loadRelations($versionInfo)->willReturn([$relation]);

        $contentView->addParameters(['relations' => [$relation]])->shouldBeCalled();

        $this->supply($contentView);
    }
}
