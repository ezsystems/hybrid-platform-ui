<?php

/**
 * File containing the DashboardController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use EzSystems\HybridPlatformUi\Components\MainContent;
use EzSystems\HybridPlatformUi\Dashboard\Dashboard;
use EzSystems\PlatformUIBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    protected $dashboard;

    public function __construct(Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    public function viewDashboardAction(MainContent $mainContent)
    {
        $mainContent->setTemplate('EzSystemsHybridPlatformUiBundle::dashboard.html.twig');
        $mainContent->setParameters(['dashboard' => $this->dashboard]);

        return $mainContent;
    }

    public function viewTabAction(Request $request, $sectionIdentifier, $tabIdentifier)
    {
        $content = $this->dashboard
            ->getSection($sectionIdentifier)
            ->getTab($tabIdentifier);

        return new Response(
            $content->render(
                $request->get('page', 1)
            )
        );
    }
}
