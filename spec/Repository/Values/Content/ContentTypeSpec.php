<?php

namespace spec\EzSystems\HybridPlatformUi\Repository\Values\Content;

use eZ\Publish\Core\Repository\Values\ContentType\ContentType;
use eZ\Publish\Core\Repository\Values\ContentType\FieldDefinition;
use PhpSpec\ObjectBehavior;

class ContentTypeSpec extends ObjectBehavior
{
    function it_should_get_all_empty_field_definitions_when_empty_field_group()
    {
        $fieldDefinition1 = new FieldDefinition(['fieldGroup' => '']);
        $fieldDefinition2 = new FieldDefinition(['fieldGroup' => 'content']);
        $fieldDefinition3 = new FieldDefinition(['fieldGroup' => '']);

        $fieldDefinitions = [$fieldDefinition1, $fieldDefinition2, $fieldDefinition3];
        $contentType = new ContentType(['fieldDefinitions' => $fieldDefinitions]);

        $this->beConstructedWith($contentType);

        $this->getFieldDefinitionsByFieldGroup()->shouldBeLike([$fieldDefinition1, $fieldDefinition3]);
    }

    function it_should_get_field_definitions_from_field_group()
    {
        $fieldGroup = 'Content';
        $fieldDefinition1 = new FieldDefinition(['fieldGroup' => '']);
        $fieldDefinition2 = new FieldDefinition(['fieldGroup' => $fieldGroup]);
        $fieldDefinition3 = new FieldDefinition(['fieldGroup' => $fieldGroup]);

        $fieldDefinitions = [$fieldDefinition1, $fieldDefinition2, $fieldDefinition3];

        $contentType = new ContentType(['fieldDefinitions' => $fieldDefinitions]);

        $this->beConstructedWith($contentType);

        $this->getFieldDefinitionsByFieldGroup($fieldGroup)->shouldBeLike([$fieldDefinition2, $fieldDefinition3]);
    }
}
