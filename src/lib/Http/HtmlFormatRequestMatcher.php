<?php

namespace EzSystems\HybridPlatformUi\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcher;

class HtmlFormatRequestMatcher extends RequestMatcher
{
    public function __construct()
    {
        parent::__construct(null, null, null, null, ['_format' => '^(?!js).*$']);
    }
    public function match(Request $request)
    {
        return parent::matches($request);
    }
}
