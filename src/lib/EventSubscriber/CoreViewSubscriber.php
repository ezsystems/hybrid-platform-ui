<?php

namespace EzSystems\HybridPlatformUi\EventSubscriber;

use eZ\Publish\Core\MVC\Symfony\View\View;
use EzSystems\HybridPlatformUi\Mapper\MainContentMapper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Remaps an EzPublishCore controller view admin requests to a MainComponent.
 */
class CoreViewSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestMatcherInterface
     */
    private $adminRequestMatcher;

    /**
     * @var \EzSystems\HybridPlatformUi\Mapper\MainContentMapper
     */
    private $mapper;

    public function __construct(
        MainContentMapper $coreViewMapper,
        RequestMatcherInterface $adminRequestMatcher
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
