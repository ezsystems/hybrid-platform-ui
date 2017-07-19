<?php

namespace EzSystems\HybridPlatformUi\EventSubscriber;

use EzSystems\HybridPlatformUi\App\ToolbarsConfigurator;
use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Http\AjaxUpdateRequestMatcher;
use EzSystems\HybridPlatformUi\Http\HybridRequestMatcher;
use EzSystems\HybridPlatformUi\Http\PartialHtmlRequestMatcher;
use EzSystems\HybridPlatformUi\Http\Response\NoRenderResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @var \EzSystems\HybridPlatformUi\App\ToolbarsConfigurator
     */
    private $toolbarsConfigurator;

    /**
     * @var \EzSystems\HybridPlatformUi\Http\AjaxUpdateRequestMatcher
     */
    private $ajaxUpdateRequestMatcher;

    /**
     * @var \EzSystems\HybridPlatformUi\Http\PartialHtmlRequestMatcher
     */
    private $partialHtmlRequestMatcher;

    public function __construct(
        App $app,
        HybridRequestMatcher $hybridRequestMatcher,
        AjaxUpdateRequestMatcher $ajaxUpdateRequestMatcher,
        PartialHtmlRequestMatcher $partialHtmlRequestMatcher,
        ToolbarsConfigurator $toolbarsConfigurator
    ) {
        $this->app = $app;
        $this->hybridRequestMatcher = $hybridRequestMatcher;
        $this->ajaxUpdateRequestMatcher = $ajaxUpdateRequestMatcher;
        $this->toolbarsConfigurator = $toolbarsConfigurator;
        $this->partialHtmlRequestMatcher = $partialHtmlRequestMatcher;
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

        $event->setResponse($this->renderAppResponse($request));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function renderAppResponse(Request $request)
    {
        if ($this->ajaxUpdateRequestMatcher->matches($request)) {
            $response = new JsonResponse($this->app);
        } else {
            $response = new Response(
                $this->app->renderToString(
                    $this->partialHtmlRequestMatcher->matches($request)
                )
            );
        }

        return $response;
    }
}
