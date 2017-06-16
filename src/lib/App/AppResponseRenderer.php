<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\HybridPlatformUi\App;

use Exception;
use EzSystems\HybridPlatformUi\Components\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Renders an App into a Response.
 */
interface AppResponseRenderer
{
    /**
     * Renders $app, and sets the result into the given $response.
     *
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \EzSystems\HybridPlatformUi\Components\App $app
     *
     * @return void
     */
    public function render(Response $response, App $app);

    /**
     * Renders the app with an exception into a given Response.
     *
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \Exception $exception
     * @param \EzSystems\HybridPlatformUi\Components\App $app
     *
     * @return
     */
    public function renderException(Response $response, Exception $exception, App $app);
}
