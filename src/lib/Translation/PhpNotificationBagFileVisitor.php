<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Translation;

use JMS\TranslationBundle\Translation\Extractor\File\DefaultPhpFileExtractor;

class PhpNotificationBagFileVisitor extends DefaultPhpFileExtractor
{
    /**
     * Methods and "domain" parameter offset to extract from PHP code.
     *
     * @var array method => position of the "domain" parameter
     */
    protected $methodsToExtractFrom = ['addsuccess' => 2, 'adderror' => 2];
}