<?php

namespace EzSystems\HybridPlatformUi\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

class AjaxUpdateRequestMatcher implements RequestMatcherInterface
{
    public function matches(Request $request)
    {
        return (bool)$request->headers->get('x-ajax-update', 0);
    }
}
