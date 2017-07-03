<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Http;

class ChainHybridRequestMatcher extends ChainRequestMatcher implements HybridRequestMatcher
{
    public function __construct(
        AdminRequestMatcher $adminRequestMatcher,
        HtmlFormatRequestMatcher $htmlFormatRequestMatcher
    ) {
        parent::__construct($adminRequestMatcher, $htmlFormatRequestMatcher);
    }
}
