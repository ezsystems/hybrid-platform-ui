<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\HybridPlatformUi\EventSubscriber;

use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Mapper\MainContentMapper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Catches admin PJAX requests, and maps them to Hybrid views.
 */
class PjaxSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestMatcherInterface
     */
    private $adminRequestMatcher;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestMatcherInterface
     */
    private $pjaxRequestMatcher;

    /**
     * @var \EzSystems\HybridPlatformUi\Mapper\PjaxHtmlToMainContentMapper
     */
    private $responseMapper;

    public static function getSubscribedEvents()
    {
        return [KernelEvents::RESPONSE => ['mapPjaxResponseToMainContent', 20]];
    }

    public function __construct(
        MainContentMapper $responseMapper,
        RequestMatcherInterface $adminRequestMatcher,
        RequestMatcherInterface $pjaxRequestMatcher
    ) {
        $this->responseMapper = $responseMapper;
        $this->adminRequestMatcher = $adminRequestMatcher;
        $this->pjaxRequestMatcher = $pjaxRequestMatcher;
    }

    public function mapPjaxResponseToMainContent(FilterResponseEvent $event)
    {
        $request = $event->getRequest();

        if (
            $event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST ||
            !$this->adminRequestMatcher->matches($request) ||
            !$this->pjaxRequestMatcher->matches($request)
        ) {
            return;
        }

        $response = $event->getResponse();

        if ($response instanceof RedirectResponse) {
            $event->stopPropagation();
            return;
        }

        $this->responseMapper->map($response->getContent());
        $response->setContent('');
    }
}
