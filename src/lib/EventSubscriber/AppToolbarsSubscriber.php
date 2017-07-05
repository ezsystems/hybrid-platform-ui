<?php

namespace EzSystems\HybridPlatformUi\EventSubscriber;

use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Http\HybridRequestMatcher;
use EzSystems\HybridPlatformUi\Toolbars\ToolbarsConfigurator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AppToolbarsSubscriber implements EventSubscriberInterface
{
    /**
     * @var \EzSystems\HybridPlatformUi\Components\App
     */
    private $app;

    /**
     * @var \EzSystems\HybridPlatformUi\Toolbars\ToolbarsConfigurator
     */
    private $configurator;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestMatcherInterface
     */
    private $hybridRequestMatcher;

    public function __construct(
        HybridRequestMatcher $hybridRequestMatcher,
        ToolbarsConfigurator $configurator
    ) {
        $this->configurator = $configurator;
        $this->hybridRequestMatcher = $hybridRequestMatcher;
    }

    public static function getSubscribedEvents()
    {
        return [KernelEvents::REQUEST => ['configureAppToolbars']];
    }

    public function configureAppToolbars(GetResponseEvent $event)
    {
        if (!$this->hybridRequestMatcher->matches($event->getRequest())) {
            return false;
        }

        $this->configurator->fromRequest($event->getRequest());
    }
}
