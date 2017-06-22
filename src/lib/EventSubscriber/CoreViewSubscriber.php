<?php

namespace EzSystems\HybridPlatformUi\EventSubscriber;

use eZ\Publish\Core\MVC\Symfony\View\View;
use EzSystems\HybridPlatformUi\Http\AdminRequestMatcher;
use EzSystems\HybridPlatformUi\View\CoreViewMainContentMapper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Remaps an EzPublishCore controller view admin requests to a MainComponent.
 */
class CoreViewSubscriber implements EventSubscriberInterface
{
    /**
     * @var \EzSystems\HybridPlatformUi\Http\AdminRequestMatcher
     */
    private $adminRequestMatcher;

    /**
     * @var \EzSystems\HybridPlatformUi\View\CoreViewMainContentMapper
     */
    private $mapper;

    public function __construct(
        CoreViewMainContentMapper $coreViewMapper,
        AdminRequestMatcher $adminRequestMatcher
    ) {
        $this->mapper = $coreViewMapper;
        $this->adminRequestMatcher = $adminRequestMatcher;
    }

    public static function getSubscribedEvents()
    {
        return [KernelEvents::VIEW => ['mapAdminViewToMainComponent', 10]];
    }

    public function mapAdminViewToMainComponent(GetResponseForControllerResultEvent $event)
    {
        if (!$this->adminRequestMatcher->matches($event->getRequest())) {
            return;
        }

        if (!($view = $event->getControllerResult()) instanceof View) {
            return;
        }

        $this->mapper->map($view);
        $event->setResponse(new Response());
    }
}
