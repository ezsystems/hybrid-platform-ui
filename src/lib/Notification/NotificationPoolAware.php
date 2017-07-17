<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Notification;

use Symfony\Component\Translation\Translator;

/**
 * Provides setters for NotificationPool and Translator,
 * Facilitates autowiring of them by the service container when implemented.
 */
interface NotificationPoolAware
{
    /**
     * Set Notification Pool.
     *
     * @param NotificationPool $notificationPool
     */
    public function setNotificationPool(NotificationPool $notificationPool);

    /**
     * Set Translator.
     *
     * @param Translator $translator
     */
    public function setTranslator(Translator $translator);
}
