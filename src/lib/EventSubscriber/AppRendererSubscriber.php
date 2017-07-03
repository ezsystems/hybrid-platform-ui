<?php

namespace EzSystems\HybridPlatformUi\EventSubscriber;

use EzSystems\HybridPlatformUi\App\AppResponseRenderer;
use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Http\HybridRequestMatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class AppRendererSubscriber implements EventSubscriberInterface
{
    /**
     * @var \EzSystems\HybridPlatformUi\Http\HybridRequestMatcher
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
        HybridRequestMatcher $hybridRequestMatcher
    ) {
        $this->hybridRequestMatcher = $hybridRequestMatcher;
        $this->app = $app;
        $this->appRenderer = $appRenderer;
    }

    public static function getSubscribedEvents()
    {
        return [KernelEvents::RESPONSE => ['renderApp', 5]];
    }

    public function renderApp(FilterResponseEvent $event)
    {
        if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
            return;
        }

        $response = $event->getResponse();

        if ($response->isRedirect()) {
            return;
        }

        $request = $event->getRequest();

        if (!$this->hybridRequestMatcher->matches($request)) {
            return;
        }

        $this->appRenderer->render($response, $this->app);
    }
}
