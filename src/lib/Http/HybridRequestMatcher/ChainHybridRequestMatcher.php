<?php

namespace EzSystems\HybridPlatformUi\Http\HybridRequestMatcher;

use EzSystems\HybridPlatformUi\Http\AdminRequestMatcher;
use EzSystems\HybridPlatformUi\Http\HtmlFormatRequestMatcher;
use EzSystems\HybridPlatformUi\Http\HybridRequestMatcher;
use Symfony\Component\HttpFoundation\Request;

/**
 * Matches a request using a set of request matchers.
 */
class ChainHybridRequestMatcher implements HybridRequestMatcher
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestMatcherInterface[]
     */
    private $requestMatchers;

    public function __construct(AdminRequestMatcher $adminRequestMatcher, HtmlFormatRequestMatcher $htmlFormatRequestMatcher)
    {
        $this->requestMatchers = func_get_args();
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
