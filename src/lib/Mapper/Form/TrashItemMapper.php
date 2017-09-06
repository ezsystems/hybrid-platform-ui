<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Mapper\Form;

use EzSystems\HybridPlatformUi\Repository\Values\Content\UiTrashItem;

/**
 * Maps location information to expected formats.
 */
class TrashItemMapper
{
    /**
     * Map locations and content to data required in form.
     *
     * @param UiTrashItem[] $uiTrashItems
     *
     * @return array
     */
    public function mapToForm(array $uiTrashItems)
    {
        $data = [
            'trashItems' => [],
        ];

        foreach ($uiTrashItems as $item) {
            $data['trashItems'][$item->id] = false;
        }

        return $data;
    }
}
