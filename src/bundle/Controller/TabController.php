<?php
/**
 * File containing the TabController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\Core\MVC\Symfony\Controller\Controller;
use EzSystems\HybridPlatformUi\Http\Response\ResetResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

abstract class TabController extends Controller
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var ContentService
     */
    protected $contentService;

    public function __construct(
        RouterInterface $router,
        ContentService $contentService
    ) {
        $this->router = $router;
        $this->contentService = $contentService;
    }

    protected function resetToMainLocation($contentId)
    {
        $mainLocationId = $this->contentService->loadContentInfo($contentId)->mainLocationId;

        return new ResetResponse($this->generateContentViewUrl($contentId, $mainLocationId));
    }

    protected function resetLocation(Location $location)
    {
        return new ResetResponse($this->generateContentViewUrl($location->contentId, $location->id));
    }

    protected function reloadTab($tab, $contentId, $locationId)
    {
        return $this->redirectToTab($contentId, $locationId, $tab . '_tab');
    }

    private function redirectToTab($contentId, $locationId, $viewType)
    {
        return new RedirectResponse(
            $this->generateContentViewUrl($contentId, $locationId, $viewType)
        );
    }

    private function generateContentViewUrl($contentId, $locationId, $viewType = 'full')
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
