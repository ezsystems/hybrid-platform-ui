<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\Exceptions\UnauthorizedException;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\Relation;
use eZ\Publish\API\Repository\Values\Content\VersionInfo;
use EzSystems\HybridPlatformUi\Repository\Values\Content\UiRelation;

/**
 * Service for loading relations with additional data not provided by the original API.
 */
class UiRelationService
{
    /**
     * @var ContentService
     */
    private $contentService;

    /**
     * @var ContentTypeService
     */
    private $contentTypeService;

    /**
     * @var LocationService
     */
    private $locationService;

    public function __construct(
        ContentService $contentService,
        ContentTypeService $contentTypeService,
        LocationService $locationService
    ) {
        $this->contentService = $contentService;
        $this->contentTypeService = $contentTypeService;
        $this->locationService = $locationService;
    }

    /**
     * Loads relations for versions.
     * Retrieves relations and then sets the field definition and the content type name.
     *
     * @param VersionInfo $versionInfo
     *
     * @return UiRelation[]
     */
    public function loadRelations(VersionInfo $versionInfo)
    {
        $relations = $this->contentService->loadRelations($versionInfo);

        return $this->buildDestinationUiRelations($relations);
    }

    /**
     * Loads reverse relations for a piece of content.
     * Retrieves reverse relations and then sets the field definition and the content type name.
     * Returns false if the user doesn't have permission to load reverse relations.
     *
     * @param ContentInfo $contentInfo
     *
     * @return UiRelation[]|bool
     */
    public function loadReverseRelations(ContentInfo $contentInfo)
    {
        try {
            $reverseRelations = $this->contentService->loadReverseRelations($contentInfo);
        } catch (UnauthorizedException $e) {
            return false;
        }

        return $this->buildSourceUiRelations($reverseRelations);
    }

    private function buildDestinationUiRelations(array $relations)
    {
        return array_map(
            function (Relation $relation) {
                return $this->buildUiRelation($relation, $relation->getDestinationContentInfo());
            },
            $relations
        );
    }

    private function buildSourceUiRelations(array $relations)
    {
        return array_map(
            function (Relation $relation) {
                return $this->buildUiRelation($relation, $relation->getSourceContentInfo());
            },
            $relations
        );
    }

    private function buildUiRelation(Relation $relation, ContentInfo $contentInfo)
    {
        $contentType = $this->loadContentType($contentInfo);

        $fieldDefinition = $contentType->getFieldDefinition($relation->sourceFieldDefinitionIdentifier);
        $destinationContentType = $this->loadContentType($contentInfo);

        $properties = [
            'relationFieldDefinitionName' => $fieldDefinition ? $fieldDefinition->getName() : '',
            'relationContentTypeName' => $destinationContentType->getName(),
            'relationLocation' => $this->locationService->loadLocation($contentInfo->mainLocationId),
            'relationName' => $contentInfo->name,
        ];

        return new UiRelation($relation, $properties);
    }

    private function loadContentType(ContentInfo $contentInfo)
    {
        return $this->contentTypeService->loadContentType($contentInfo->contentTypeId);
    }
}
