<?php

namespace EzSystems\HybridPlatformUi\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

class HardcodedAdminRequestMatcher implements RequestMatcherInterface
{
    public function matches(Request $request)
    {
        // @todo can't be hardcoded
        return strpos($request->getRequestUri(), '/admin') === 0;
    }
}
