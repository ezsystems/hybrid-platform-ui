<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Notification;

use Symfony\Component\Translation\Translator;

/**
 * Provides methods for adding success and error notifications.
 * Implements the methods defined in the NotificationPoolAware to facilitate setting NotificationPool and Translator.
 */
trait NotificationPoolAwareTrait
{
    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var NotificationPool
     */
    protected $notificationPool;

    /**
     * Set Notification Pool.
     *
     * @param NotificationPool $notificationPool
     */
    public function setNotificationPool(NotificationPool $notificationPool)
    {
        $this->notificationPool = $notificationPool;
    }

    /**
     * Set Translator.
     *
     * @param Translator $translator
     */
    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Add success notification.
     *
     * @param string $message
     * @param array $params
     * @param string|null $domain
     */
    protected function addSuccessNotification(string $message, array $params = [], $domain = null)
    {
        $notification = new Notification([
            'type' => Notification::TYPE_SUCCESS,
            /** @Ignore */
            'message' => $this->translator->trans($message, $params, $domain),
            'timeout' => Notification::DEFAULT_TIMEOUT,
        ]);

        $this->notificationPool->add($notification);
    }

    /**
     * Add error notification.
     *
     * @param string $message
     * @param array $params
     * @param string|null $domain
     * @param string $details
     */
    protected function addErrorNotification(string $message, array $params = [], $domain = null, string $details)
    {
        $notification = new Notification([
            'type' => Notification::TYPE_ERROR,
            /** @Ignore */
            'message' => $this->translator->trans($message, $params, $domain),
            'timeout' => Notification::ERROR_TIMEOUT,
            'copyable' => true,
            'details' => $details,
        ]);

        $this->notificationPool->add($notification);
    }
}
