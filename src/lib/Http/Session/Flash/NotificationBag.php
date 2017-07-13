<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Http\Session\Flash;

use EzSystems\HybridPlatformUi\Notification\Notification;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Used for adding notifications.
 */
class NotificationBag
{
    const FLASH_MESSAGE_TYPE = 'hyybrid-notification';

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(Session $session, TranslatorInterface $translator)
    {
        $this->flashBag = $session->getFlashBag();
        $this->translator = $translator;
    }

    /**
     * Add success notification.
     *
     * @param string $message
     * @param array $params
     * @param null $domain
     */
    public function addSuccess(string $message, array $params = [], $domain = null)
    {
        $notification = new Notification([
            'type' => Notification::TYPE_SUCCESS,
            /** @Ignore */
            'message' => $this->translator->trans($message, $params, $domain),
            'timeout' => Notification::DEFAULT_TIMEOUT,
        ]);

        $this->flashBag->add(self::FLASH_MESSAGE_TYPE, $notification);
    }

    /**
     * Add error notification.
     *
     * @param string $message
     * @param array $params
     * @param null $domain
     * @param string $details
     */
    public function addError(string $message, array $params = [], $domain = null, string $details)
    {
        $notification = new Notification([
            'type' => Notification::TYPE_ERROR,
            /** @Ignore */
            'message' => $this->translator->trans($message, $params, $domain),
            'timeout' => Notification::ERROR_TIMEOUT,
            'copyable' => true,
            'details' => $details,
        ]);

        $this->flashBag->add(self::FLASH_MESSAGE_TYPE, $notification);
    }

    /**
     * Gets notifications from the flashbag then clears them.
     *
     * @return Notification[]
     */
    public function get()
    {
        return $this->flashBag->get(self::FLASH_MESSAGE_TYPE);
    }

    /**
     * Gets notifications from the flashbag without clearing them.
     *
     * @return Notification[]
     */
    public function peek()
    {
        return $this->flashBag->peek(self::FLASH_MESSAGE_TYPE);
    }
}
