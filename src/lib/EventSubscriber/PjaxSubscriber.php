<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\EventSubscriber;

use EzSystems\HybridPlatformUi\Http\AdminRequestMatcher;
use EzSystems\HybridPlatformUi\Mapper\MainContentMapper;
use EzSystems\HybridPlatformUi\Pjax\PjaxResponseMatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @var \EzSystems\HybridPlatformUi\Pjax\PjaxResponseMainContentMapper
     */
    private $responseMapper;

    /**
     * @var \EzSystems\HybridPlatformUi\Pjax\PjaxResponseMatcher
     */
    private $pjaxResponseMatcher;

    public static function getSubscribedEvents()
    {
        return [KernelEvents::RESPONSE => ['mapPjaxResponseToMainContent', 10]];
    }

    public function __construct(
        MainContentMapper $responseMapper,
        AdminRequestMatcher $adminRequestMatcher,
        RequestMatcherInterface $pjaxRequestMatcher,
        PjaxResponseMatcher $pjaxResponseMatcher
    ) {
        $this->responseMapper = $responseMapper;
        $this->adminRequestMatcher = $adminRequestMatcher;
        $this->pjaxRequestMatcher = $pjaxRequestMatcher;
        $this->pjaxResponseMatcher = $pjaxResponseMatcher;
    }

    public function mapPjaxResponseToMainContent(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        if (
            $event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST ||
            !$this->adminRequestMatcher->matches($request) ||
            !$this->isPjax($request, $response)
        ) {
            return;
        }

        // If AJAX update, follow the redirection and return the update for it.
        // If not an AJAX update, send the redirection.
        if ($response instanceof RedirectResponse) {
            $event->stopPropagation();

            return;
        }

        $this->responseMapper->map($response);
    }

    private function isPjax(Request $request, Response $response)
    {
        return $this->pjaxRequestMatcher->matches($request)
            || $this->pjaxResponseMatcher->matches($response);
    }
}
