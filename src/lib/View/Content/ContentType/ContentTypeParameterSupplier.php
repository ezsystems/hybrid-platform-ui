<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\View\Content\ContentType;

use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use EzSystems\HybridPlatformUi\View\Content\ParameterSupplier;

/**
 * Adds parameters needed to display content types.
 */
class ContentTypeParameterSupplier implements ParameterSupplier
{
    /**
     * @var ContentTypeService
     */
    protected $contentTypeService;

    public function __construct(ContentTypeService $contentTypeService)
    {
        $this->contentTypeService = $contentTypeService;
    }

    /**
     * {@inheritdoc}
     */
    public function supply(ContentView $contentView)
    {
        $contentType = $this->contentTypeService->loadContentType(
            $contentView->getContent()->contentInfo->contentTypeId
        );

        $contentView->addParameters(['contentType' => $contentType]);
    }
}
