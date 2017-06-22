<?php

namespace EzSystems\HybridPlatformUi\Http\HtmlFormatRequestMatcher;

use EzSystems\HybridPlatformUi\Http\HtmlFormatRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher;

/**
 * Matches an HTML format request using the _format request attribute.
 */
class AttributeHtmlFormatRequestMatcher extends RequestMatcher implements HtmlFormatRequestMatcher
{
    public function __construct()
    {
        parent::__construct(null, null, null, null, ['_format' => '^(?!js).*$']);
    }
}
