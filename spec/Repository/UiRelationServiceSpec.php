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
use eZ\Publish\Core\REST\Client\Exceptions\UnauthorizedException;
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
        ContentType $contentType,
        ContentType $relationContentType,
        FieldDefinition $fieldDefinition,
        LocationService $locationService
    ) {
        $contentTypeId = 12;
        $relationContentTypeId = 13;
        $sourceFieldDefinitionIdentifier = 'image';
        $relationContentTypeName = 'Article';
        $locationId = 1;

        $location = new Location(['id' => $locationId]);

        $relationContentInfo = new ContentInfo(
            [
                'contentTypeId' => $relationContentTypeId,
                'mainLocationId' => $locationId,
            ]
        );

        $relation = new Relation(
            [
                'sourceFieldDefinitionIdentifier' => $sourceFieldDefinitionIdentifier,
                'destinationContentInfo' => $relationContentInfo,
            ]
        );
        $contentInfo = new ContentInfo(['contentTypeId' => $contentTypeId]);

        $contentService->loadRelations($versionInfo)->willReturn([
            $relation,
        ])->shouldBeCalled();

        $versionInfo->getContentInfo()->willReturn($contentInfo)->shouldBeCalled();
        $contentTypeService->loadContentType($contentTypeId)->willReturn($contentType)->shouldBeCalled();
        $contentTypeService->loadContentType($relationContentTypeId)->willReturn($relationContentType)->shouldBeCalled();
        $contentType->getFieldDefinition($sourceFieldDefinitionIdentifier)->willReturn($fieldDefinition)->shouldBeCalled();
        $locationService->loadLocation($locationId)->willReturn($location)->shouldBeCalled();
        $relationContentType->getName()->willReturn($relationContentTypeName);
        $fieldDefinition->getName()->willReturn(Relation::FIELD);

        $uiRelation = new UiRelation(
            $relation,
            [
                'fieldDefinitionName' => Relation::FIELD,
                'destinationContentTypeName' => $relationContentTypeName,
                'destinationLocation' => $location,
            ]
        );

        $this->loadRelations($versionInfo)->shouldBeLike([$uiRelation]);
    }

    function it_handles_user_not_having_correct_permissions(ContentService $contentService, ContentInfo $contentInfo)
    {
        $contentService->loadReverseRelations($contentInfo)->willThrow(new UnauthorizedException());
        $this->loadReverseRelations($contentInfo)->shouldBe([]);
    }
}
