<?php

namespace EzSystems\HybridPlatformUi\Dashboard\Tab;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\SearchService;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\Core\Pagination\Pagerfanta\ContentSearchAdapter;
use EzSystems\HybridPlatformUi\Dashboard\PaginatedTab;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class AllMedia extends PaginatedTab
{
    /** @var ContentService */
    protected $contentService;

    /** @var ContentTypeService */
    protected $contentTypeService;

    /** @var SearchService */
    protected $searchService;

    /**
     * @param EngineInterface $templating
     * @param RouterInterface $router
     * @param ContentService $contentService
     * @param ContentTypeService $contentTypeService
     * @param SearchService $searchService
     */
    public function __construct(
        EngineInterface $templating,
        RouterInterface $router,
        ContentService $contentService,
        ContentTypeService $contentTypeService,
        SearchService $searchService
    ) {
        parent::__construct($templating, $router);

        $this->contentService = $contentService;
        $this->contentTypeService = $contentTypeService;
        $this->searchService = $searchService;
    }

    /**
     * @param Pagerfanta $pager
     *
     * @return array
     */
    private function getData(Pagerfanta $pager): array
    {
        $data = [];
        foreach ($pager as $content) {
            $contentInfo = $this->contentService->loadContentInfo($content->id);
            $data[] = [
                'contentId' => $content->id,
                'name' => $contentInfo->name,
                'language' => $contentInfo->mainLanguageCode,
                'version' => $content->versionInfo->versionNo,
                'type' => $this->contentTypeService->loadContentType($contentInfo->contentTypeId)->getName(),
                'modified' => $content->versionInfo->modificationDate,
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
        $query = new Query();
        $query->filter = new Query\Criterion\Subtree('/1/43/');
        $query->sortClauses = [new Query\SortClause\DateModified(Query::SORT_DESC)];

        $pager = new Pagerfanta(
            new ContentSearchAdapter($query, $this->searchService)
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
