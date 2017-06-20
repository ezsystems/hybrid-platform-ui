<?php

namespace EzSystems\HybridPlatformUi\NavigationHub\Link;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Subtree extends Route
{
    public function __construct(UrlGeneratorInterface $urlGenerator, $name, $zone, array $routeParams = [])
    {
        parent::__construct($urlGenerator, 'ez_urlalias', $name, $zone, $routeParams);
    }

    public function match(Request $request)
    {
        $location = $request->attributes->get('location');

        return
            $this->matchRoute($this->routeName)
            && $location
            && in_array((string)$this->routeParams['locationId'], $location->path)
        ;
    }
}
