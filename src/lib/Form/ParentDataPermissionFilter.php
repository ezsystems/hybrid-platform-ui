<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\HybridPlatformUi\Form;

use Symfony\Component\Form\FormEvent;

class ParentDataPermissionFilter implements PermissionFilter
{
    public function __invoke()
    {
        return [$this, 'applyPermissions'];
    }

    public function applyPermissions(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $form->getParent()->getData();
        $permissionDataName = 'can' . ucfirst($form->getName());

        if (!isset($data[$permissionDataName])) {
            return;
        }

        $canData = $data[$permissionDataName];
        foreach ($form->all() as $name => $field)
        {
            $options = $field->getConfig()->getOptions();
            if ($canData[$name] === false) {
                $options['disabled'] = true;
                $form->add($name, get_class($field->getConfig()->getType()->getInnerType()), $options);
            }
        }
    }
}
