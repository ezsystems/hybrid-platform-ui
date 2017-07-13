<?php

namespace spec\EzSystems\HybridPlatformUi\View\Content\Locations;

use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use eZ\Publish\Core\Repository\Values\Content\Content;
use eZ\Publish\Core\Repository\Values\Content\VersionInfo;
use EzSystems\HybridPlatformUi\Repository\UiLocationService;
use EzSystems\HybridPlatformUi\Repository\Values\Content\UiLocation;
use EzSystems\HybridPlatformUi\View\Content\ParameterSupplier;
use PhpSpec\ObjectBehavior;

class LocationParameterSupplierSpec extends ObjectBehavior
{
    function let(UiLocationService $uiLocationService)
    {
        $this->beConstructedWith($uiLocationService);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ParameterSupplier::class);
    }

    function it_should_supply_us_with_a_list_of_locations(
        UiLocationService $uiLocationService,
        ContentView $contentView,
        UiLocation $uiLocation
    ) {
        $contentInfo = new ContentInfo();
        $versionInfo = new VersionInfo(['contentInfo' => $contentInfo]);
        $content = new Content(['versionInfo' => $versionInfo]);
        $contentView->getContent()->willReturn($content);

        $uiLocationService->loadLocations($contentInfo)->willReturn([$uiLocation]);

        $contentView->addParameters(['locations' => [$uiLocation]])->shouldBeCalled();

        $this->supply($contentView);
    }
}
