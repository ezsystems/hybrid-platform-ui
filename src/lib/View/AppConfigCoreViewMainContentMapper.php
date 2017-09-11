<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\View;

use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use eZ\Publish\Core\MVC\Symfony\View\View;
use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Components\MainContent;

/**
 * Configures the app's main content based on a CoreView.
 */
class AppConfigCoreViewMainContentMapper implements CoreViewMainContentMapper
{
    /**
     * @var \EzSystems\HybridPlatformUi\Components\App
     */
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @param \eZ\Publish\Core\MVC\Symfony\View\View $view
     */
    public function map($view)
    {
        if (!$view instanceof View) {
            throw new \InvalidArgumentException('Expected an \eZ\Publish\Core\MVC\Symfony\View\View');
        }

        $this->app->setConfig([
            'mainContent' => [
                'template' => $view->getTemplateIdentifier(),
                'parameters' => $view->getParameters(),
            ],
            'title' => $this->getTitle($view),
        ]);
    }

    private function getTitle(View $view)
    {
        if ($view instanceof ContentView) {
            return $view->getContent()->getName();
        } else {
            if ($view->hasParameter('title')) {
                return $view->getParameter('title');
            }
        }
    }
}
