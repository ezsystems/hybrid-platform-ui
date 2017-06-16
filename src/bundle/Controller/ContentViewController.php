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

class ContentViewController extends Controller
{
    protected $defaultSortFields = [
        Location::SORT_FIELD_NAME => 'sort.name',
        Location::SORT_FIELD_PRIORITY => 'sort.priority',
        Location::SORT_FIELD_MODIFIED => 'sort.modified',
        Location::SORT_FIELD_PUBLISHED => 'sort.published'
    ];

    protected $otherSortFields = [
        Location::SORT_FIELD_PATH => 'sort.path',
        Location::SORT_FIELD_CLASS_IDENTIFIER => 'sort.content.type.identifier',
        Location::SORT_FIELD_SECTION => 'sort.section',
        Location::SORT_FIELD_DEPTH => 'sort.depth',
        Location::SORT_FIELD_CLASS_NAME => 'sort.content.type.name',
    ];

    public function detailsTabAction(ContentView $view)
    {
        $repository = $this->getRepository();

        $versionInfo = $view->getContent()->getVersionInfo();
        $contentInfo = $versionInfo->getContentInfo();

        $sectionService = $this->getRepository()->getSectionService();
        $section = $sectionService->loadSection($contentInfo->sectionId);

        $userService = $this->getRepository()->getUserService();

        $creator = $repository->sudo(function () use ($contentInfo, $userService) {
            try {
                return $userService->loadUser($contentInfo->ownerId);
            } catch (NotFoundException $e) {
                return null;
            }
        });

        $lastContributor = $repository->sudo(function () use ($versionInfo, $userService) {
            try {
                return $userService->loadUser($versionInfo->creatorId);
            } catch (NotFoundException $e) {
                return null;
            }
        });

        $location = $view->getLocation();

        $view->addParameters([
            'section' => $section,
            'contentInfo' => $contentInfo,
            'versionInfo' => $versionInfo,
            'creator' => $creator,
            'lastContributor' => $lastContributor,
            'translations' => $this->getTranslations($versionInfo),
            'ordering' => [
                'sortFields' => $this->getSortFields($location->sortField),
                'sortOrders' => $this->getSortOrders()
            ]
        ]);

        return $view;
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
            Location::SORT_ORDER_ASC => 'locationview.details.ascending',
            Location::SORT_ORDER_DESC => 'locationview.details.descending'
        ];
    }
}
