<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\HybridPlatformUi\Mapper;

use EzSystems\HybridPlatformUi\Components\MainContent;

/**
 * Maps a complete HTML response to a MainContent.
 */
class FullHtmlToMainContentMapper extends HtmlToMainContentMapper
{
    /**
     * @var \EzSystems\HybridPlatformUi\Components\MainContent
     */
    private $mainContent;

    public function __construct(MainContent $mainContent)
    {
        $this->mainContent = $mainContent;
    }

    /**
     * Returns the xpath that extracts the body.
     *
     * @return string
     */
    protected function getBodyXpath()
    {
        return '//html';
    }

    protected function setBody($body)
    {
        $this->mainContent->setResult($body);
    }
}
