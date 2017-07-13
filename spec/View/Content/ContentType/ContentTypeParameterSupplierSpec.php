<?php

namespace spec\EzSystems\HybridPlatformUi\View\Content\ContentType;

use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\ContentType\ContentType;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use eZ\Publish\Core\Repository\Values\Content\Content;
use eZ\Publish\Core\Repository\Values\Content\VersionInfo;
use EzSystems\HybridPlatformUi\View\Content\ParameterSupplier;
use PhpSpec\ObjectBehavior;

class ContentTypeParameterSupplierSpec extends ObjectBehavior
{
    function let(ContentTypeService $contentTypeService)
    {
        $this->beConstructedWith($contentTypeService);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ParameterSupplier::class);
    }

    function it_should_supply_us_with_a_content_type(
        ContentTypeService $contentTypeService,
        ContentView $contentView,
        ContentType $contentType
    ) {
        $contentTypeId = 1;

        $contentInfo = new ContentInfo(['contentTypeId' => $contentTypeId]);
        $versionInfo = new VersionInfo(['contentInfo' => $contentInfo]);
        $content = new Content(['versionInfo' => $versionInfo]);
        $contentView->getContent()->willReturn($content);

        $contentTypeService->loadContentType($contentTypeId)->willReturn($contentType);

        $contentView->addParameters(['contentType' => $contentType])->shouldBeCalled();

        $this->supply($contentView);
    }
}
