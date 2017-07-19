<?php

namespace spec\EzSystems\HybridPlatformUi\View\Content\Urls;

use eZ\Publish\API\Repository\URLAliasService;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\API\Repository\Values\Content\URLAlias;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use EzSystems\HybridPlatformUi\View\Content\ParameterSupplier;
use PhpSpec\ObjectBehavior;

class CustomUrlParameterSupplierSpec extends ObjectBehavior
{
    function let(URLAliasService $URLAliasService)
    {
        $this->beConstructedWith($URLAliasService);
    }

    function it_is_a_supplier()
    {
        $this->shouldBeAnInstanceOf(ParameterSupplier::class);
    }

    function it_should_supply_us_with_a_list_of_custom_urls(
        ContentView $contentView,
        Location $location,
        URLAliasService $URLAliasService,
        URLAlias $URLAlias
    ) {
        $URLAliases = [$URLAlias, $URLAlias];

        $contentView->getLocation()->willReturn($location);
        $URLAliasService->listLocationAliases($location, true)->willReturn($URLAliases);

        $contentView->addParameters(['customUrls' => $URLAliases])->shouldBeCalled();

        $this->supply($contentView);
    }
}
