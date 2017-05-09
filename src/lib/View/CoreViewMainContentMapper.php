<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\HybridPlatformUi\View;

use eZ\Publish\Core\MVC\Symfony\View\View;
use EzSystems\HybridPlatformUi\Components\MainContent;
use EzSystems\HybridPlatformUi\Mapper\MainContentMapper;

class CoreViewMainContentMapper implements MainContentMapper
{
    /**
     * @var MainContent
     */
    private $mainContent;

    public function __construct(MainContent $mainContent)
    {
        $this->mainContent = $mainContent;
    }

    /**
     * @param \eZ\Publish\Core\MVC\Symfony\View\View $view
     *
     * @return \EzSystems\HybridPlatformUi\Components\MainContent
     */
    public function map($view)
    {
        if (!$view instanceof View) {
            throw new \InvalidArgumentException('Expected an \eZ\Publish\Core\MVC\Symfony\View\View');
        }

        $this->mainContent->setTemplate($view->getTemplateIdentifier());
        $this->mainContent->setParameters($view->getParameters());

        return $this->mainContent;
    }
}
