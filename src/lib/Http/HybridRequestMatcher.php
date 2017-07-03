<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Http;

use Symfony\Component\HttpFoundation\RequestMatcherInterface;

/**
 * Matches hybrid UI requests to be rendered within the Hybrid UI app.
 */
interface HybridRequestMatcher extends RequestMatcherInterface
{
}
