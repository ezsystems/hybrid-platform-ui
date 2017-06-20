<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\App;

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
     */
    public function render(Response $response, App $app);
}
