<?php

namespace EzSystems\HybridPlatformUi\Http\AjaxUpdateRequestMatcher;

use EzSystems\HybridPlatformUi\Http\AjaxUpdateRequestMatcher;
use Symfony\Component\HttpFoundation\Request;

class HeaderAjaxUpdateRequestMatcher implements AjaxUpdateRequestMatcher
{
    public function matches(Request $request)
    {
        return $request->headers->has('x-ajax-update');
    }
}
