<?php

namespace EzSystems\HybridPlatformUi\Toolbars;

use EzSystems\HybridPlatformUi\Components\App;
use Symfony\Component\HttpFoundation\Request;

class RouteBasedConfigurator implements ToolbarsConfigurator
{
    /**
     * @var App
     */
    private $app;

    /**
     * @var array
     */
    private $mappings = [];

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->mappings = [
            'ez_urlalias' => ['discovery' => 1],
        ];
    }

    public function addRoutesMappings(array $mappings)
    {
        $this->mappings = array_merge($mappings);
    }

    public function fromRequest(Request $request)
    {
        if (!$request->attributes->has('_route')) {
            return;
        }

        $route = $request->attributes->get('_route');

        if (!isset($this->mappings[$route])) {
            return;
        }

        $this->app->setConfig(['toolbars' => $this->mappings[$route]]);
    }
}
