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

class DashboardController extends Controller
{
    public function viewDashboardAction()
    {
        $mainContent = $this->container->get('ezsystems.platformui.component.maincontent');
        $mainContent->setTemplate('EzSystemsHybridPlatformUiBundle::dashboard.html.twig');

        return $mainContent;
    }
}
