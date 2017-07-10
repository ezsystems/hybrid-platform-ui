<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Builder;

/**
 * Factory for new notification builder instances.
 */
class NotificationBuilderFactory
{
    /**
     * Create new builder instance.
     *
     * @return NotificationBuilder
     */
    public function create()
    {
        return new NotificationBuilder();
    }
}
