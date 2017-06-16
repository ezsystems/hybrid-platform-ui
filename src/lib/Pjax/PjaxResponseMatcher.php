<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Pjax;

use EzSystems\HybridPlatformUi\Http\ResponseMatcherInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Matches a Pjax Http Response.
 */
interface PjaxResponseMatcher extends ResponseMatcherInterface
{
    public function matches(Response $response);
}
