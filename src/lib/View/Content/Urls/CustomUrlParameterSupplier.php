<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\View\Content\Urls;

use eZ\Publish\API\Repository\URLAliasService;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use EzSystems\HybridPlatformUi\View\Content\ParameterSupplier;

/**
 * Adds parameters needed to display custom urls.
 */
class CustomUrlParameterSupplier implements ParameterSupplier
{
    /**
     * @var URLAliasService
     */
    private $URLAliasService;

    public function __construct(URLAliasService $URLAliasService)
    {
        $this->URLAliasService = $URLAliasService;
    }

    public function supply(ContentView $contentView)
    {
        $contentView->addParameters([
            'customUrls' => $this->URLAliasService->listLocationAliases($contentView->getLocation(), true),
        ]);
    }
}
