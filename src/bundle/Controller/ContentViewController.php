<?php

/**
 * File containing the ContentViewController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use EzSystems\HybridPlatformUi\Repository\UiFieldGroupService;
use EzSystems\HybridPlatformUi\Repository\UiRelationService;
use EzSystems\HybridPlatformUi\Repository\UiTranslationService;
use EzSystems\HybridPlatformUi\Repository\UiUserService;

class ContentViewController extends TabController
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

    public function contentTabAction(ContentView $view, UiFieldGroupService $fieldGroupService)
    {
        $versionInfo = $view->getContent()->getVersionInfo();

        $view->addParameters([
            'fieldGroups' => $fieldGroupService->loadFieldGroups($versionInfo->getContentInfo()),
        ]);

        return $view;
    }

    public function detailsTabAction(
        ContentView $view,
        UiUserService $userService,
        UiTranslationService $translationService
    ) {
        $versionInfo = $view->getContent()->getVersionInfo();
        $contentInfo = $versionInfo->getContentInfo();

        $sectionService = $this->getRepository()->getSectionService();
        $section = $sectionService->loadSection($contentInfo->sectionId);

        $view->addParameters([
            'section' => $section,
            'contentInfo' => $contentInfo,
            'versionInfo' => $versionInfo,
            'creator' => $userService->findUserById($contentInfo->ownerId),
            'lastContributor' => $userService->findUserById($versionInfo->creatorId),
            'translations' => $translationService->loadTranslations($versionInfo),
            'ordering' => [
                'sortFields' => $this->getSortFields($view->getLocation()->sortField),
                'sortOrders' => $this->getSortOrders(),
            ],
        ]);

        return $view;
    }

    public function relationsTabAction(ContentView $view, UiRelationService $relationService)
    {
        $versionInfo = $view->getContent()->getVersionInfo();
        $contentInfo = $versionInfo->getContentInfo();

        $view->addParameters([
            'relations' => $relationService->loadRelations($versionInfo),
            'reverseRelations' => $relationService->loadReverseRelations($contentInfo),
        ]);

        return $view;
    }

    public function translationsTabAction(ContentView $view, UiTranslationService $translationService)
    {
        $view->addParameters([
            'translations' => $translationService->loadTranslations($view->getContent()->getVersionInfo()),
        ]);

        return $view;
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
