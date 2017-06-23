<?php

namespace spec\EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\ContentType\ContentType as APIContentType;
use EzSystems\HybridPlatformUi\Repository\Values\Content\ContentType;
use PhpSpec\ObjectBehavior;

class ContentTypeServiceSpec extends ObjectBehavior
{
    function let(ContentTypeService $contentTypeService)
    {
        $this->beConstructedWith($contentTypeService);
    }

    function it_loads_and_creates_content_type(ContentTypeService $contentTypeService, APIContentType $contentType)
    {
        $contentTypeId = 1;
        $contentInfo = new ContentInfo(['contentTypeId' => $contentTypeId]);

        $contentTypeService->loadContentType($contentTypeId)->willReturn($contentType)->shouldBeCalled();

        $this->loadContentType($contentInfo)->shouldHaveType(ContentType::class);
    }
}
