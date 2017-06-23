<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\ContentTypeService as APIContentTypeService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use EzSystems\HybridPlatformUi\Repository\Values\Content\ContentType;

/**
 * Service for loading content type with additional data not provided by the original API.
 */
class ContentTypeService
{
    /**
     * @var APIContentTypeService
     */
    private $contentTypeService;

    public function __construct(APIContentTypeService $contentTypeService)
    {
        $this->contentTypeService = $contentTypeService;
    }

    /**
     * Gets a content type and will return an instance of content type that provides further functionality
     *
     * @param ContentInfo $contentInfo
     *
     * @return ContentType
     */
    public function loadContentType(ContentInfo $contentInfo)
    {
        $contentType = $this->contentTypeService->loadContentType($contentInfo->contentTypeId);

        return new ContentType($contentType);
    }
}
