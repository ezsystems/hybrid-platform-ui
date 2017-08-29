<?php

namespace EzSystems\HybridPlatformUi\Dashboard\Tab;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\ContentTypeService;
use EzSystems\HybridPlatformUi\Dashboard\PaginatedTab;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class MyDrafts extends PaginatedTab
{
    /** @var ContentService */
    protected $contentService;

    /** @var ContentTypeService */
    protected $contentTypeService;

    /**
     * MyDrafts constructor.
     * @param EngineInterface $templating
     * @param RouterInterface $router
     * @param ContentService $contentService
     * @param ContentTypeService $contentTypeService
     */
    public function __construct(
        EngineInterface $templating,
        RouterInterface $router,
        ContentService $contentService,
        ContentTypeService $contentTypeService
    ) {
        parent::__construct($templating, $router);

        $this->contentService = $contentService;
        $this->contentTypeService = $contentTypeService;
    }

    /**
     * @param Pagerfanta $pager
     *
     * @return array
     */
    private function getData(Pagerfanta $pager): array
    {
        $data = [];
        foreach ($pager as $version) {
            $contentInfo = $version->getContentInfo();
            $data[] = [
                'contentId' => $contentInfo->id,
                'name' => $contentInfo->name,
                'type' => $this->contentTypeService->loadContentType($contentInfo->contentTypeId)->getName(),
                'language' => $version->initialLanguageCode,
                'version' => $version->versionNo,
                'modified' => $version->modificationDate,
            ];
        }

        return $data;
    }

    /**
     * @param int $page
     *
     * @return string
     */
    public function render($page = 1): string
    {
        $pager = new Pagerfanta(
            new ArrayAdapter(
                $this->contentService->loadContentDrafts()
            )
        );
        $pager->setMaxPerPage(10);
        $pager->setCurrentPage($page);

        $this->setParameter('current_page', $page);
        $this->setParameter('data', $this->getData($pager));
        $this->setParameter('pages_urls', $this->getPaginatorUrls(
            $page,
            $pager->getNbPages()
        ));

        return parent::render();
    }
}
