<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcher;

/**
 * Matches an HTML request using the _format request attribute.
 */
class FormatAttributeHtmlFormatRequestMatcher extends RequestMatcher implements HtmlFormatRequestMatcher
{
    public function matches(Request $request)
    {
        $this->matchAttribute('_format', '^(?!js).*$');

        return parent::matches($request);
    }
}
