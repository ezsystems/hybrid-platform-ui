<?php

/**
 * File containing the DashboardController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Components\MainContent;
use EzSystems\PlatformUIBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\Location;

class DashboardController extends Controller
{
    public function viewDashboardAction()
    {
        $mainContent = $this->container->get('ezsystems.platformui.component.maincontent');
        $mainContent->setTemplate('EzSystemsHybridPlatformUiBundle::dashboard.html.twig');

        return $mainContent;
    }
}
