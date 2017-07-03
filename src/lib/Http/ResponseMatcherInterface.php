<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Http;

use Symfony\Component\HttpFoundation\Response;

interface ResponseMatcherInterface
{
    /**
     * @param $response \Symfony\Component\HttpFoundation\Response
     * @return bool
     */
    public function matches(Response $response);
}
