<?php

namespace EzSystems\HybridPlatformUi\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

/**
 * Matches an HTML format request using the _format request attribute.
 */
interface HtmlFormatRequestMatcher extends RequestMatcherInterface
{
}
