<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Pjax;

use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Components\MainContent;
use EzSystems\HybridPlatformUi\Mapper\MainContentMapper;
use Symfony\Component\HttpFoundation\Response;

/**
 * Maps a PJAX Response to the App's mainContent.
 */
interface PjaxResponseMainContentMapper extends MainContentMapper
{
    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @throws \Exception
     */
    public function map($response);
}
