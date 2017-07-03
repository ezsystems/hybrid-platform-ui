<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

/**
 * Matches a request to the admin interface.
 *
 * Allows to define blacklist route prefixes that must not be matched as admin.
 */
interface AdminRequestMatcher extends RequestMatcherInterface
{
    /**
     * Defines the excluded route prefixes.
     * Requests with a route that match an excluded prefix are not matched.
     *
     * @param array $routes
     */
    public function setExcludedRoutesPrefixes($routes);

    public function matches(Request $request);
}
