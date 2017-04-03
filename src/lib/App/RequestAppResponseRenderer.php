<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\HybridPlatformUi\App;

use EzSystems\HybridPlatformUi\Components\App;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class RequestAppResponseRenderer implements AppResponseRenderer
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestMatcherInterface
     */
    private $ajaxUpdateRequestMatcher;

    public function __construct(
        RequestStack $requestStack,
        RequestMatcherInterface $ajaxUpdateRequestMatcher
    ) {

        $this->requestStack = $requestStack;
        $this->ajaxUpdateRequestMatcher = $ajaxUpdateRequestMatcher;
    }

    public function render(Response $response, App $app)
    {
        $appResponse = $this->ajaxUpdateRequestMatcher->matches($this->requestStack->getMasterRequest())
            ? new JsonResponse($app)
            : new Response($app);

        $response
            ->setContent($appResponse->getContent())
            ->headers->replace($appResponse->headers->all());
    }
}
