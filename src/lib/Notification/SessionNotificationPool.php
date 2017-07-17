<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Notification;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Session based NotificationPool implementation.
 */
class SessionNotificationPool implements NotificationPool
{
    const TYPE = 'hybrid-notification';

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(Session $session)
    {
        $this->flashBag = $session->getFlashBag();
    }

    /**
     * {@inheritdoc}
     */
    public function add(Notification $notification)
    {
        return $this->flashBag->add(self::TYPE, $notification);
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        return $this->flashBag->get(self::TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function peek()
    {
        return $this->flashBag->peek(self::TYPE);
    }
}
