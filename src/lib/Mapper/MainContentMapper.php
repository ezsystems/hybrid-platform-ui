<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\HybridPlatformUi\Mapper;

use EzSystems\PlatformUIBundle\Components\MainContent;
use Symfony\Component\HttpFoundation\Response;

/**
 * Maps an object to a MainComponent.
 */
interface MainContentMapper
{
    /**
     * @param mixed $value
     *
     * @return \EzSystems\HybridPlatformUi\Components\MainContent
     */
    public function map($value);
}
