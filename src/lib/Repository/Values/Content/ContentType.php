<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository\Values\Content;

use eZ\Publish\API\Repository\Values\ContentType\ContentType as APIContentType;

/**
 * Adds a new method to content type that allows us to provide extra functionality.
 */
class ContentType extends \eZ\Publish\Core\Repository\Values\ContentType\ContentType
{
    public function __construct(APIContentType $contentType)
    {
        parent::__construct(get_object_vars($contentType));
    }

    /**
     * Gets the content type field definitions based on the field group.
     * If no field group is provided it will return all field definitions that have an empty field group.
     *
     * @param string|null $fieldGroup
     *
     * @return array
     */
    public function getFieldDefinitionsByFieldGroup($fieldGroup = null)
    {
        $fieldDefinitions = [];
        foreach ($this->getFieldDefinitions() as $fieldDefinition) {
            if ($fieldGroup == $fieldDefinition->fieldGroup) {
                $fieldDefinitions[] = $fieldDefinition;
            }
        }

        return $fieldDefinitions;
    }
}
