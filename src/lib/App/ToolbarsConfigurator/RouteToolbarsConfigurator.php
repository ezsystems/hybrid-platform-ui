<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\App\ToolbarsConfigurator;

use EzSystems\HybridPlatformUi\App\ToolbarsConfigurator;
use EzSystems\HybridPlatformUi\Components\App;
use Symfony\Component\HttpFoundation\RequestStack;

class RouteToolbarsConfigurator implements ToolbarsConfigurator
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    // Adding discovery to 1 allows to make the toolbar visible: see EZP-27322
    protected $routesConfiguration = [
        'ez_urlalias' => ['discovery' => 1],
        '_ez_content_view' => ['discovery' => 1],
        'ez_hybrid_platform_ui_trash_view' => ['discovery' => 1],
    ];

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function configureToolbars(App $app)
    {
        $route = $this->requestStack->getMasterRequest()->attributes->get('_route');

        if (isset($this->routesConfiguration[$route])) {
            $app->setConfig(['toolbars' => $this->routesConfiguration[$route]]);
        }
    }
}
