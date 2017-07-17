<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Notification;

/**
 * Interface for storing notifications.
 */
interface NotificationPool
{
    /**
     * Add notification.
     *
     * @param Notification $notification
     */
    public function add(Notification $notification);

    /**
     * Get and clears notifications.
     *
     * @return Notification[]
     */
    public function get();

    /**
     * Gets notifications without clearing.
     *
     * @return Notification[]
     */
    public function peek();
}
