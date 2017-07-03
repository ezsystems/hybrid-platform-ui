<?php

namespace EzSystems\HybridPlatformUi\Http;

use Symfony\Component\HttpFoundation\Request;

/**
 * Matches admin requests using the siteaccess URI part.
 */
class HardcodedAdminRequestMatcher implements AdminRequestMatcher
{
    /**
     * @var array
     */
    private $excludedRoutes = [];

    /**
     * @param array $routes
     */
    public function setExcludedRoutesPrefixes($routes)
    {
        $this->excludedRoutes = $routes;
    }

    public function matches(Request $request)
    {
        if (strpos($request->getRequestUri(), '/admin') !== 0) {
            return false;
        }

        $routeName = $request->attributes->get('_route');
        foreach ($this->excludedRoutes as $prefix) {
            if (strpos($routeName, $prefix) === 0) {
                return false;
            }
        }

        return true;
    }
}
