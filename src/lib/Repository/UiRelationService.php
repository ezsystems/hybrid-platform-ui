<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\ContentTypeService;
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

        return $this->buildUiRelations($relations, $versionInfo->getContentInfo());
    }

    /**
     * Loads reverse relations for a piece of content.
     * Retrieves reverse relations and then sets the field definition and the content type name.
     *
     * @param ContentInfo $contentInfo
     *
     * @return UiRelation[]
     */
    public function loadReverseRelations(ContentInfo $contentInfo)
    {
        $reverseRelations = $this->contentService->loadReverseRelations($contentInfo);

        return $this->buildUiRelations($reverseRelations, $contentInfo);
    }

    private function buildUiRelations(array $relations, ContentInfo $contentInfo)
    {
        return array_map(
            function (Relation $relation) use ($contentInfo) {
                $contentType = $this->loadContentType($contentInfo);
                $destinationContentInfo = $relation->getDestinationContentInfo();

                $fieldDefinition = $contentType->getFieldDefinition($relation->sourceFieldDefinitionIdentifier);
                $destinationContentType = $this->loadContentType($destinationContentInfo);

                $properties = [
                    'fieldDefinitionName' => $fieldDefinition->getName(),
                    'destinationContentTypeName' => $destinationContentType->getName(),
                    'destinationLocation' => $this->locationService->loadLocation($destinationContentInfo->mainLocationId),
                ];

                return new UiRelation($relation, $properties);
            },
            $relations
        );
    }

    private function loadContentType(ContentInfo $contentInfo)
    {
        return $this->contentTypeService->loadContentType($contentInfo->contentTypeId);
    }
}
