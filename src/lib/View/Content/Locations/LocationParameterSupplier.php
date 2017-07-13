<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\View\Content\Locations;

use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use EzSystems\HybridPlatformUi\Repository\UiLocationService;
use EzSystems\HybridPlatformUi\View\Content\ParameterSupplier;

/**
 * Adds parameters needed to display locations.
 */
class LocationParameterSupplier implements ParameterSupplier
{
    /**
     * @var UiLocationService
     */
    private $uiLocationService;

    public function __construct(UiLocationService $uiLocationService)
    {
        $this->uiLocationService = $uiLocationService;
    }

    /**
     * {@inheritdoc}
     */
    public function supply(ContentView $contentView)
    {
        $contentView->addParameters([
            'locations' => $this->uiLocationService->loadLocations($contentView->getContent()->contentInfo),
        ]);
    }
}
