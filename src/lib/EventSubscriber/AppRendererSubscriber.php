<?php

namespace EzSystems\HybridPlatformUi\EventSubscriber;

use EzSystems\HybridPlatformUi\App\AppResponseRenderer;
use EzSystems\HybridPlatformUi\App\ToolbarsConfigurator;
use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Http\HybridRequestMatcher;
use EzSystems\HybridPlatformUi\Http\Response\NoRenderResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
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

    /**
     * @var \EzSystems\HybridPlatformUi\App\ToolbarsConfigurator
     */
    private $toolbarsConfigurator;

    public function __construct(
        App $app,
        AppResponseRenderer $appRenderer,
        HybridRequestMatcher $hybridRequestMatcher,
        ToolbarsConfigurator $toolbarsConfigurator
    ) {
        $this->hybridRequestMatcher = $hybridRequestMatcher;
        $this->app = $app;
        $this->appRenderer = $appRenderer;
        $this->toolbarsConfigurator = $toolbarsConfigurator;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => ['renderApp', 5],
        ];
    }

    public function renderApp(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $response = $event->getResponse();

        if ($response->isRedirect() || $response instanceof NoRenderResponse) {
            return;
        }

        $request = $event->getRequest();

        if (!$this->hybridRequestMatcher->matches($request)) {
            return;
        }

        $this->toolbarsConfigurator->configureToolbars($this->app);
        $this->appRenderer->render($response, $this->app);
    }
}
