<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\HybridPlatformUi\Form;

use Symfony\Component\Form\FormEvent;

interface PermissionFilter
{
    public function __invoke();

    public function applyPermissions(FormEvent $formEvent);
}
