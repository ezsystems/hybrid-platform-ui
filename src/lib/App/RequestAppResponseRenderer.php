<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\App;

use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Http\AjaxUpdateRequestMatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestAppResponseRenderer implements AppResponseRenderer
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestMatcherInterface
     */
    private $ajaxUpdateRequestMatcher;

    public function __construct(
        Request $request,
        AjaxUpdateRequestMatcher $ajaxUpdateRequestMatcher
    ) {
        $this->request = $request;
        $this->ajaxUpdateRequestMatcher = $ajaxUpdateRequestMatcher;
    }

    public function render(Response $response, App $app)
    {
        $appResponse = $this->ajaxUpdateRequestMatcher->matches($this->request)
            ? new JsonResponse($app)
            : new Response($app);

        $response
            ->setContent($appResponse->getContent())
            ->headers->replace($appResponse->headers->all());
    }
}
