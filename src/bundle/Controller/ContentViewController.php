<?php

/**
 * File containing the ContentViewController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\API\Repository\Values\Content\VersionInfo;
use eZ\Publish\Core\MVC\Symfony\Controller\Controller;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use EzSystems\HybridPlatformUi\Filter\VersionFilter;

class ContentViewController extends Controller
{
    protected $defaultSortFields = [
        Location::SORT_FIELD_NAME => ['key' => 'sort.name', 'default' => 'Content name'],
        Location::SORT_FIELD_PRIORITY => ['key' => 'sort.priority', 'default' => 'Priority'],
        Location::SORT_FIELD_MODIFIED => ['key' => 'sort.modified', 'default' => 'Modification date'],
        Location::SORT_FIELD_PUBLISHED => ['key' => 'sort.published', 'default' => 'Publication date'],
    ];

    protected $otherSortFields = [
        Location::SORT_FIELD_PATH => ['key' => 'sort.path', 'default' => 'Location path'],
        Location::SORT_FIELD_CLASS_IDENTIFIER => [
            'key' => 'sort.content.type.identifier',
            'default' => 'Content type identifier',
        ],
        Location::SORT_FIELD_SECTION => ['key' => 'sort.section', 'default' => 'Section'],
        Location::SORT_FIELD_DEPTH => ['key' => 'sort.depth', 'default' => 'Location depth'],
        Location::SORT_FIELD_CLASS_NAME => ['key' => 'sort.content.type.name', 'default' => 'Content type name'],
    ];

    /**
     * @var VersionFilter
     */
    private $versionFilter;

    public function __construct(VersionFilter $versionFilter)
    {
        $this->versionFilter = $versionFilter;
    }

    public function detailsTabAction(ContentView $view)
    {
        $versionInfo = $view->getContent()->getVersionInfo();
        $contentInfo = $versionInfo->getContentInfo();

        $sectionService = $this->getRepository()->getSectionService();
        $section = $sectionService->loadSection($contentInfo->sectionId);

        $view->addParameters([
            'section' => $section,
            'contentInfo' => $contentInfo,
            'versionInfo' => $versionInfo,
            'creator' => $this->loadUser($contentInfo->ownerId),
            'lastContributor' => $this->loadUser($versionInfo->creatorId),
            'translations' => $this->getTranslations($versionInfo),
            'ordering' => [
                'sortFields' => $this->getSortFields($view->getLocation()->sortField),
                'sortOrders' => $this->getSortOrders(),
            ],
        ]);

        return $view;
    }

    public function versionsTabAction(ContentView $view)
    {
        $contentInfo = $view->getContent()->getVersionInfo()->getContentInfo();
        $contentService = $this->getRepository()->getContentService();
        $versions = $contentService->loadVersions($contentInfo);

        $authors = [];
        foreach ($versions as $version) {
            $authors[$version->id] = $this->loadUser($version->creatorId);
        }

        $translations = [];
        foreach ($versions as $version) {
            $translations[$version->id] = $this->getTranslations($version);
        }

        $view->addParameters([
            'draftVersions' => $this->versionFilter->filterDrafts($versions),
            'publishedVersions' => $this->versionFilter->filterPublished($versions),
            'archivedVersions' => $this->versionFilter->filterArchived($versions),
            'authors' => $authors,
            'translations' => $translations,
        ]);

        return $view;
    }

    public function locationsTabAction(ContentView $view)
    {
        $versionInfo = $view->getContent()->getVersionInfo();
        $contentInfo = $versionInfo->getContentInfo();

        if ($contentInfo->published) {
            $locationService = $this->container->get(
                'ezsystems.platformui.hybrid.repository.decorated_location_service'
            );
            $locations = $locationService->loadLocations($contentInfo);

            $view->addParameters([
                'locations' => $locations,
            ]);
        }

        return $view;
    }

    protected function loadUser($userId)
    {
        $userService = $this->getRepository()->getUserService();

        return $this->getRepository()->sudo(function () use ($userId, $userService) {
            try {
                return $userService->loadUser($userId);
            } catch (NotFoundException $e) {
                return null;
            }
        });
    }

    protected function getTranslations(VersionInfo $versionInfo)
    {
        $languageRepository = $this->getRepository()->getContentLanguageService();

        $translations = [];
        foreach ($versionInfo->languageCodes as $languageCode) {
            $translations[] = $languageRepository->loadLanguage($languageCode);
        }

        return $translations;
    }

    protected function getSortFields($currentSortField)
    {
        $sortFields = $this->defaultSortFields;

        if (array_key_exists($currentSortField, $this->otherSortFields)) {
            $sortFields[$currentSortField] = $this->otherSortFields[$currentSortField];
        }

        return $sortFields;
    }

    protected function getSortOrders()
    {
        return [
            Location::SORT_ORDER_ASC => ['key' => 'locationview.details.ascending', 'default' => 'Ascending'],
            Location::SORT_ORDER_DESC => ['key' => 'locationview.details.descending', 'default' => 'Descending'],
        ];
    }
}
