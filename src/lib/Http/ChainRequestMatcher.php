<?php

namespace EzSystems\HybridPlatformUi\Http;

use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Matches a request using a set of request matchers.
 */
class ChainRequestMatcher implements RequestMatcherInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestMatcherInterface[]
     */
    private $requestMatchers;

    public function __construct(RequestMatcherInterface ...$requestMatchers)
    {
        $this->requestMatchers = $requestMatchers;
    }

    /**
     * Matches the request if ALL the chained request matchers match.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
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
