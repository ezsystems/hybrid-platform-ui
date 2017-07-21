<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\EventSubscriber;

use EzSystems\HybridPlatformUi\App\ExceptionConfigurator;
use EzSystems\HybridPlatformUi\Http\HybridRequestMatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\EventListener\ExceptionListener;

/**
 * Overrides the HttpKernel Exception subscriber for hybrid UI requests.
 */
class AppExceptionSubscriber extends ExceptionListener implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Component\HttpKernel\EventListener\ExceptionListener
     */
    private $innerListener;
    /**
     * @var \EzSystems\HybridPlatformUi\Http\HybridRequestMatcher
     */
    private $hybridRequestMatcher;

    /**
     * @var \EzSystems\HybridPlatformUi\App\ExceptionConfigurator
     */
    private $configurator;

    /**
     * AppExceptionSubscriber constructor.
     *
     * @param \Symfony\Component\HttpKernel\EventListener\ExceptionListener $innerListener
     * @param \EzSystems\HybridPlatformUi\Http\HybridRequestMatcher $hybridRequestMatcher
     * @param \EzSystems\HybridPlatformUi\App\ExceptionConfigurator $exceptionConfigurator
     */
    public function __construct(ExceptionListener $innerListener, HybridRequestMatcher $hybridRequestMatcher, ExceptionConfigurator $exceptionConfigurator)
    {
        $this->innerListener = $innerListener;
        $this->hybridRequestMatcher = $hybridRequestMatcher;
        $this->configurator = $exceptionConfigurator;
    }

    /**
     * Configures the exception as rendered by the HttpKernel listener into the app so that it gets rendered.
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $this->innerListener->onKernelException($event);

        if (!$event->isMasterRequest()) {
            return;
        }

        if (!$this->hybridRequestMatcher->matches($event->getRequest())) {
            return;
        }

        $this->configurator->configureException($event->getException(), $event->getResponse());
    }
}
