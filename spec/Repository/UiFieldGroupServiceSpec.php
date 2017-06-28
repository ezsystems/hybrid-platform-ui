<?php

namespace spec\EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\ContentType\ContentType as APIContentType;
use eZ\Publish\Core\Helper\FieldsGroups\FieldsGroupsList;
use eZ\Publish\Core\Repository\Values\ContentType\FieldDefinition;
use EzSystems\HybridPlatformUi\Repository\Values\ContentType\UiFieldGroup;
use PhpSpec\ObjectBehavior;

class UiFieldGroupServiceSpec extends ObjectBehavior
{
    function let(ContentTypeService $contentTypeService, FieldsGroupsList $fieldsGroupsList)
    {
        $this->beConstructedWith($contentTypeService, $fieldsGroupsList);
    }

    function it_loads_field_groups(
        ContentTypeService $contentTypeService,
        APIContentType $contentType,
        FieldsGroupsList $fieldsGroupsList
    ) {
        $fieldGroupId1 = 'content';
        $fieldGroupId2 = 'features';
        $fieldGroupId3 = 'metadata';
        $fieldGroupName1 = 'Content';

        $fieldGroups = [$fieldGroupId1 => $fieldGroupName1, $fieldGroupId2 => 'Features', $fieldGroupId3 => 'Metadata'];
        $fieldDefinition1 = new FieldDefinition(['fieldGroup' => $fieldGroupId1]);
        $fieldDefinition2 = new FieldDefinition(['fieldGroup' => $fieldGroupId1]);

        $fieldsGroupsList->getGroups()->willReturn($fieldGroups)->shouldBeCalled();
        $contentType->getFieldDefinitions()->willReturn([
            $fieldDefinition1,
            $fieldDefinition2,
        ])->shouldBeCalled();

        $contentTypeId = 1;
        $contentInfo = new ContentInfo(['contentTypeId' => $contentTypeId]);

        $contentTypeService->loadContentType($contentTypeId)->willReturn($contentType)->shouldBeCalled();

        $fieldGroup = new UiFieldGroup([
            'id' => $fieldGroupId1,
            'name' => $fieldGroupName1,
            'fieldDefinitions' => [$fieldDefinition1, $fieldDefinition2],
        ]);

        $this->loadFieldGroups($contentInfo)->shouldBeLike([$fieldGroup]);
    }

    function it_uses_default_field_group_when_a_field_group_is_empty(
        ContentTypeService $contentTypeService,
        APIContentType $contentType,
        FieldsGroupsList $fieldsGroupsList
    ) {
        $fieldGroupId1 = 'content';
        $fieldGroupId2 = 'features';
        $fieldGroupId3 = 'metadata';
        $fieldGroupName1 = 'Content';

        $fieldGroups = [$fieldGroupId1 => $fieldGroupName1, $fieldGroupId2 => 'Features', $fieldGroupId3 => 'Metadata'];
        $fieldDefinition1 = new FieldDefinition(['fieldGroup' => '']);
        $fieldDefinition2 = new FieldDefinition(['fieldGroup' => '']);

        $fieldsGroupsList->getGroups()->willReturn($fieldGroups)->shouldBeCalled();
        $fieldsGroupsList->getDefaultGroup()->willReturn($fieldGroupId1)->shouldBeCalled();
        $contentType->getFieldDefinitions()->willReturn([
            $fieldDefinition1,
            $fieldDefinition2,
        ])->shouldBeCalled();

        $contentTypeId = 1;
        $contentInfo = new ContentInfo(['contentTypeId' => $contentTypeId]);

        $contentTypeService->loadContentType($contentTypeId)->willReturn($contentType)->shouldBeCalled();

        $fieldGroup = new UiFieldGroup([
            'id' => $fieldGroupId1,
            'name' => $fieldGroupName1,
            'fieldDefinitions' => [$fieldDefinition1, $fieldDefinition2],
        ]);

        $this->loadFieldGroups($contentInfo)->shouldBeLike([$fieldGroup]);
    }
}
