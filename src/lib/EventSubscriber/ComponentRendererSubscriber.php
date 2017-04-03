<?php

namespace EzSystems\HybridPlatformUi\EventSubscriber;

use EzSystems\HybridPlatformUi\Components\Component;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ComponentRendererSubscriber implements EventSubscriberInterface
{
    private $ajaxUpdateRequestMatcher;

    public function __construct(RequestMatcherInterface $ajaxUpdateRequestMatcher)
    {
        $this->ajaxUpdateRequestMatcher = $ajaxUpdateRequestMatcher;
    }

    public static function getSubscribedEvents()
    {
        return [KernelEvents::VIEW => ['renderComponent']];
    }

    public function renderComponent(GetResponseForControllerResultEvent $event)
    {
        if (!($component = $event->getControllerResult()) instanceof Component) {
            return;
        }

        $response = $this->ajaxUpdateRequestMatcher->matches($event->getRequest()) ?
            new JsonResponse($component) :
            new Response($component);

        $event->setResponse($response);
    }
}
