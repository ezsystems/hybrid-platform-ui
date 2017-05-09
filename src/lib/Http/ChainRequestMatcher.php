<?php

namespace EzSystems\HybridPlatformUi\Http;

use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class ChainRequestMatcher implements RequestMatcherInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestMatcherInterface[]
     */
    private $requestMatchers;

    public function __construct(RequestMatcherInterface...$requestMatchers)
    {
        $this->requestMatchers = $requestMatchers;
    }

    public function matches(Request $request)
    {
        foreach ($this->requestMatchers as $requestMatcher) {
            if (!$requestMatcher->matches($request)) {
                return false;
            }
        }

        return true;
    }
}
