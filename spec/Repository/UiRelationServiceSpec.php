<?php

namespace spec\EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\VersionInfo;
use eZ\Publish\API\Repository\Values\ContentType\ContentType;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\Repository\Values\Content\Location;
use eZ\Publish\Core\Repository\Values\Content\Relation;
use EzSystems\HybridPlatformUi\Repository\Values\Content\UiRelation;
use PhpSpec\ObjectBehavior;

class UiRelationServiceSpec extends ObjectBehavior
{
    function let(
        ContentService $contentService,
        ContentTypeService $contentTypeService,
        LocationService $locationService
    ) {
        $this->beConstructedWith($contentService, $contentTypeService, $locationService);
    }

    function it_loads_relations_with_location_field_definition_and_content_type(
        ContentService $contentService,
        ContentTypeService $contentTypeService,
        VersionInfo $versionInfo,
        ContentType $relationContentType,
        FieldDefinition $fieldDefinition,
        LocationService $locationService
    ) {
        $relationContentTypeId = 13;
        $sourceFieldDefinitionIdentifier = 'image';
        $relationContentTypeName = 'Article';
        $locationId = 1;
        $relationName = 'Linked products';

        $location = new Location(['id' => $locationId]);

        $relationContentInfo = new ContentInfo(
            [
                'contentTypeId' => $relationContentTypeId,
                'mainLocationId' => $locationId,
                'name' => $relationName,
            ]
        );

        $relation = new Relation(
            [
                'sourceFieldDefinitionIdentifier' => $sourceFieldDefinitionIdentifier,
                'destinationContentInfo' => $relationContentInfo,
            ]
        );

        $contentService->loadRelations($versionInfo)->willReturn([
            $relation,
        ])->shouldBeCalled();

        $contentTypeService->loadContentType($relationContentTypeId)->willReturn($relationContentType)->shouldBeCalled();
        $relationContentType->getFieldDefinition($sourceFieldDefinitionIdentifier)->willReturn($fieldDefinition)->shouldBeCalled();
        $locationService->loadLocation($locationId)->willReturn($location)->shouldBeCalled();
        $relationContentType->getName()->willReturn($relationContentTypeName);
        $fieldDefinition->getName()->willReturn(Relation::FIELD);

        $uiRelation = new UiRelation(
            $relation,
            [
                'relationFieldDefinitionName' => Relation::FIELD,
                'relationContentTypeName' => $relationContentTypeName,
                'relationLocation' => $location,
                'relationName' => $relationName,
            ]
        );

        $this->loadRelations($versionInfo)->shouldBeLike([$uiRelation]);
    }

    function it_loads_reverse_relations_with_location_field_definition_and_content_type(
        ContentService $contentService,
        ContentTypeService $contentTypeService,
        ContentInfo $contentInfo,
        ContentType $relationContentType,
        FieldDefinition $fieldDefinition,
        LocationService $locationService
    ) {
        $relationContentTypeId = 13;
        $sourceFieldDefinitionIdentifier = 'image';
        $relationContentTypeName = 'Article';
        $locationId = 1;
        $relationName = 'Linked products';

        $location = new Location(['id' => $locationId]);

        $relationContentInfo = new ContentInfo(
            [
                'contentTypeId' => $relationContentTypeId,
                'mainLocationId' => $locationId,
                'name' => $relationName,
            ]
        );

        $relation = new Relation(
            [
                'sourceFieldDefinitionIdentifier' => $sourceFieldDefinitionIdentifier,
                'sourceContentInfo' => $relationContentInfo,
            ]
        );

        $contentService->loadReverseRelations($contentInfo)->willReturn([
            $relation,
        ])->shouldBeCalled();

        $contentTypeService->loadContentType($relationContentTypeId)->willReturn($relationContentType)->shouldBeCalled();
        $relationContentType->getFieldDefinition($sourceFieldDefinitionIdentifier)->willReturn($fieldDefinition)->shouldBeCalled();
        $locationService->loadLocation($locationId)->willReturn($location)->shouldBeCalled();
        $relationContentType->getName()->willReturn($relationContentTypeName);
        $fieldDefinition->getName()->willReturn(Relation::FIELD);

        $uiRelation = new UiRelation(
            $relation,
            [
                'relationFieldDefinitionName' => Relation::FIELD,
                'relationContentTypeName' => $relationContentTypeName,
                'relationLocation' => $location,
                'relationName' => $relationName,
            ]
        );

        $this->loadReverseRelations($contentInfo)->shouldBeLike([$uiRelation]);
    }
}
