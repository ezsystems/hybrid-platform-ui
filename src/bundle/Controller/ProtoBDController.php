<?php

/**
 * File containing the DashboardController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use EzSystems\HybridPlatformUi\Components\MainContent;
use EzSystems\PlatformUIBundle\Controller\Controller;

class ProtoBDController extends Controller
{
    public function dashboardAction()
    {
        // @todo Fix this
        $content = new MainContent($this->container->get('templating'));
        $content->setTemplate('EzSystemsHybridPlatformUiBundle::dashboard.html.twig');

        return $content;
    }
}
