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
use EzSystems\HybridPlatformUi\Mapper\Form\VersionMapper;
use EzSystems\HybridPlatformUi\Repository\VersionService;
use EzSystems\HybridPlatformUiBundle\Form\Versions\ArchivedActions;
use EzSystems\HybridPlatformUiBundle\Form\Versions\DraftActions;
use Symfony\Component\HttpFoundation\Request;
use EzSystems\HybridPlatformUi\Repository\DecoratedLocationService;

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

    public function versionsTabAction(
        ContentView $view,
        VersionFilter $versionFilter,
        VersionMapper $versionMapper
    ) {
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

        $draftVersions = $versionFilter->filterDrafts($versions);
        $draftActionsForm = $this->createForm(DraftActions::class);
        $draftActionsForm->setData($versionMapper->mapToForm($draftVersions, $contentInfo));

        $archivedVersions = $versionFilter->filterArchived($versions);
        $archivedActionsForm = $this->createForm(ArchivedActions::class);
        $archivedActionsForm->setData($versionMapper->mapToForm($archivedVersions, $contentInfo));

        $view->addParameters([
            'draftVersions' => $draftVersions,
            'publishedVersions' => $versionFilter->filterPublished($versions),
            'archivedVersions' => $archivedVersions,
            'authors' => $authors,
            'translations' => $translations,
            'draftActionsForm' => $draftActionsForm->createView(),
            'archivedActionsForm' => $archivedActionsForm->createView(),
        ]);

        return $view;
    }

    public function draftActionsAction(Request $request, VersionService $versionService)
    {
        $draftActionsForm = $this->createForm(DraftActions::class);
        $draftActionsForm->handleRequest($request);

        if ($draftActionsForm->isValid()) {
            $selectedIds = $draftActionsForm->get('versionIds')->getData();
            $contentId = (int)$draftActionsForm->get('contentId')->getData();

            if ($draftActionsForm->get('delete')->isClicked()) {
                foreach (array_keys($selectedIds) as $versionId) {
                    $versionService->deleteVersion($contentId, $versionId);
                }
            }
        }
        //@TODO Show success/fail message to user
        return $this->redirectToRoute('ez_hybrid_platform_ui_dashboard');
    }

    public function locationsTabAction(ContentView $view, DecoratedLocationService $decoratedLocationService)
    {
        $versionInfo = $view->getContent()->getVersionInfo();
        $contentInfo = $versionInfo->getContentInfo();

        if ($contentInfo->published) {
            $locations = $decoratedLocationService->loadLocations($contentInfo);

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
