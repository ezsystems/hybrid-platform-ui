<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\HybridPlatformUi\App;

use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Components\MainContent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
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
        RequestMatcherInterface $ajaxUpdateRequestMatcher
    ) {
        $this->request = $request;
        $this->ajaxUpdateRequestMatcher = $ajaxUpdateRequestMatcher;
    }

    public function render(Response $response, App $app)
    {
        $this->configureToolbars($app);

        $appResponse = $this->ajaxUpdateRequestMatcher->matches($this->request)
            ? new JsonResponse($app)
            : new Response($app);

        $response
            ->setContent($appResponse->getContent())
            ->headers->replace($appResponse->headers->all());
    }

    /**
     * Configures the toolbars.
     *
     * @todo Depends on the Request. See http://github.com/ezsystems/hybrid-platform-ui/pull/4
     */
    private function configureToolbars(App $app)
    {
        $app->setConfig(['toolbars' => ['discovery' => 1]]);
    }
}
