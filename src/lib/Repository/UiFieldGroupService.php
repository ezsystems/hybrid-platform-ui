<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\Core\Helper\FieldsGroups\FieldsGroupsList;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use EzSystems\HybridPlatformUi\Repository\Values\ContentType\UiFieldGroup;

/**
 * Service for loading Field groups.
 */
class UiFieldGroupService
{
    /**
     * @var ContentTypeService
     */
    private $contentTypeService;
    /**
     * @var FieldsGroupsList
     */
    private $fieldsGroupsList;

    public function __construct(ContentTypeService $contentTypeService, FieldsGroupsList $fieldsGroupsList)
    {
        $this->contentTypeService = $contentTypeService;
        $this->fieldsGroupsList = $fieldsGroupsList;
    }

    /**
     * Loads field groups for a piece of content and includes the group name.
     *
     * @param ContentInfo $contentInfo
     *
     * @return UiFieldGroup[]
     */
    public function loadFieldGroups(ContentInfo $contentInfo)
    {
        $contentType = $this->contentTypeService->loadContentType($contentInfo->contentTypeId);
        $fields = $contentType->getFieldDefinitions();

        $fieldGroups = [];
        foreach ($this->fieldsGroupsList->getGroups() as $groupId => $groupName) {
            $fieldsInGroup = $this->filterFieldDefinitionsByGroup($fields, $groupId);

            if ($fieldsInGroup) {
                $fieldGroups[] = new UiFieldGroup([
                    'id' => $groupId,
                    'name' => $groupName,
                    'fieldDefinitions' => $fieldsInGroup,
                ]);
            }
        }

        return $fieldGroups;
    }

    private function filterFieldDefinitionsByGroup(array $fields, $groupId)
    {
        $filterByGroupIdOrDefault = function (FieldDefinition $field) use ($groupId) {
            return ($field->fieldGroup === $groupId)
                || (empty($field->fieldGroup) && $groupId === $this->fieldsGroupsList->getDefaultGroup());
        };

        return array_filter($fields, $filterByGroupIdOrDefault);
    }
}
