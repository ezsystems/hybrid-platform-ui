<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Translation;

use JMS\TranslationBundle\Translation\Extractor\File\DefaultPhpFileExtractor;

class PhpNotificationPoolFileVisitor extends DefaultPhpFileExtractor
{
    /**
     * Methods and "domain" parameter offset to extract from PHP code.
     *
     * @var array method => position of the "domain" parameter
     */
    protected $methodsToExtractFrom = ['addsuccessnotification' => 2, 'adderrornotification' => 2];
}
