<?php
/**
 * File containing the TabController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\Core\MVC\Symfony\Controller\Controller;
use EzSystems\HybridPlatformUi\Response\ResetResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

abstract class TabController extends Controller
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var ContentService
     */
    private $contentService;

    public function __construct(
        RouterInterface $router,
        ContentService $contentService
    ) {
        $this->router = $router;
        $this->contentService = $contentService;
    }

    protected function resetLocation($contentId)
    {
        $mainLocationId = $this->contentService->loadContentInfo($contentId)->mainLocationId;

        return new ResetResponse($this->generateTabUrl($contentId, $mainLocationId));
    }

    protected function redirectToLocationsTab($contentId, $locationId)
    {
        return $this->redirectToTab($contentId, $locationId, 'locations_tab');
    }

    protected function redirectToVersionsTab($contentId, $locationId)
    {
        return $this->redirectToTab($contentId, $locationId, 'versions_tab');
    }

    private function redirectToTab($contentId, $locationId, $viewType)
    {
        return new RedirectResponse(
            $this->generateTabUrl($contentId, $locationId, $viewType)
        );
    }

    private function generateTabUrl($contentId, $locationId, $viewType = 'full')
    {
        return $this->router->generate(
            '_ez_content_view',
            [
                'contentId' => $contentId,
                'locationId' => $locationId,
                'viewType' => $viewType,
            ]
        );
    }
}
