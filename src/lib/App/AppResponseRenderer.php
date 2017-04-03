<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\HybridPlatformUi\App;

use EzSystems\HybridPlatformUi\Components\App;
use Symfony\Component\HttpFoundation\Response;

interface AppResponseRenderer
{
    public function render(Response $response, App $app);
}
