<?php

namespace EzSystems\HybridPlatformUi\EventSubscriber;

use EzSystems\HybridPlatformUi\App\AppResponseRenderer;
use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Components\Component;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class AppRendererSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestMatcherInterface
     */
    private $adminRequestMatcher;

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
        RequestMatcherInterface $adminRequestMatcher
    ) {
        $this->adminRequestMatcher = $adminRequestMatcher;
        $this->app = $app;
        $this->appRenderer = $appRenderer;
    }

    public static function getSubscribedEvents()
    {
        return [KernelEvents::VIEW => ['renderApp', 5]];
    }

    public function renderApp(GetResponseForControllerResultEvent $event)
    {
        if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
            return;
        }

        $request = $event->getRequest();

        if (!$this->adminRequestMatcher->matches($request)) {
            return;
        }

        if (!($controllerResult = $event->getControllerResult()) instanceof Component) {
            return;
        }

        $this->app->setConfig([
            'mainContent' => $event->getControllerResult(),
            'toolbars' => ['discovery' => 1],
        ]);

        $event->setResponse(new Response());
        $this->appRenderer->render($event->getResponse(), $this->app);
    }
}
