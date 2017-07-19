<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Http;

use Symfony\Component\HttpFoundation\Request;

class HeaderPartialHtmlRequestMatcher implements PartialHtmlRequestMatcher
{
    const ACCEPT_HEADER = 'application/partial-update+html';

    public function matches(Request $request)
    {
        return in_array(
            self::ACCEPT_HEADER,
            $request->getAcceptableContentTypes()
        );
    }
}
