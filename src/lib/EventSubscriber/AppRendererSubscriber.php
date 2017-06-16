<?php

namespace EzSystems\HybridPlatformUi\EventSubscriber;

use EzSystems\HybridPlatformUi\App\AppResponseRenderer;
use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Components\Component;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class AppRendererSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestMatcherInterface
     */
    private $hybridRequestMatcher;

    /**
     * @var \EzSystems\HybridPlatformUi\Components\App
     */
    private $app;

    /**
     * @var \EzSystems\HybridPlatformUi\App\AppResponseRenderer
     */
    private $appRenderer;

    public function __construct(
        App $app,
        AppResponseRenderer $appRenderer,
        RequestMatcherInterface $hybridRequestMatcher
    ) {
        $this->hybridRequestMatcher = $hybridRequestMatcher;
        $this->app = $app;
        $this->appRenderer = $appRenderer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => ['renderApp', 5],
            KernelEvents::EXCEPTION => ['renderException']
        ];
    }

    public function renderApp(FilterResponseEvent $event)
    {
        if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
            return;
        }

        $request = $event->getRequest();

        if (!$this->hybridRequestMatcher->matches($request)) {
            return;
        }

        $this->appRenderer->render($event->getResponse(), $this->app);
    }

    public function renderException(GetResponseForExceptionEvent $event)
    {
        if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
            return;
        }

        $request = $event->getRequest();

        if (!$this->hybridRequestMatcher->matches($request)) {
            return;
        }

        $this->appRenderer->renderException($event->getResponse(), $event->getException());
    }
}
