<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\HybridPlatformUi\EventSubscriber;

use EzSystems\HybridPlatformUi\App\AppResponseRenderer;
use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Mapper\PjaxResponseMainContentMapper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Catches admin PJAX requests, and maps them to Hybrid views.
 */
class PjaxResponseSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestMatcherInterface
     */
    private $adminRequestMatcher;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestMatcherInterface
     */
    private $pjaxRequestMatcher;

    /**
     * @var \EzSystems\HybridPlatformUi\Mapper\PjaxResponseMainContentMapper
     */
    private $responseMapper;

    /**
     * @var \EzSystems\HybridPlatformUi\Components\App
     */
    private $app;

    /**
     * @var \EzSystems\HybridPlatformUi\App\AppResponseRenderer
     */
    private $appRenderer;

    public static function getSubscribedEvents()
    {
        return [KernelEvents::RESPONSE => ['mapPjaxResponseToHybridResponse', 10]];
    }

    public function __construct(
        App $app,
        AppResponseRenderer $appRenderer,
        PjaxResponseMainContentMapper $responseMapper,
        RequestMatcherInterface $adminRequestMatcher,
        RequestMatcherInterface $pjaxRequestMatcher
    ) {
        $this->adminRequestMatcher = $adminRequestMatcher;
        $this->app = $app;
        $this->appRenderer = $appRenderer;
        $this->pjaxRequestMatcher = $pjaxRequestMatcher;
        $this->responseMapper = $responseMapper;
    }

    public function mapPjaxResponseToHybridResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();

        if (
            $event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST ||
            !$this->adminRequestMatcher->matches($request) ||
            !$this->pjaxRequestMatcher->matches($request)
        ) {
            return;
        }

        $response = $event->getResponse();
        if ($response instanceof RedirectResponse) {
            $event->setResponse(
                new RedirectResponse($response->headers->get('PJAX-Location'))
            );
            $event->stopPropagation();
            return;
        }

        $this->app->setConfig([
            'mainContent' => $this->responseMapper->map($response),
            'toolbars' => ['discovery' => 1],
        ]);

        $this->appRenderer->render($event->getResponse(), $this->app);
    }
}
