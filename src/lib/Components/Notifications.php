<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Components;

use EzSystems\HybridPlatformUi\Notification\Notification;
use EzSystems\HybridPlatformUi\Notification\NotificationPool;
use Symfony\Component\Templating\EngineInterface;

class Notifications implements Component
{
    const NOTIFICATION_TEMPLATE = 'EzSystemsHybridPlatformUiBundle:components:notification.html.twig';

    public $exception = null;

    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    private $templating;

    /**
     * @var \EzSystems\HybridPlatformUi\Notification\NotificationPool
     */
    private $notificationPool;

    public function __construct(EngineInterface $templating, NotificationPool $notificationPool)
    {
        $this->templating = $templating;
        $this->notificationPool = $notificationPool;
    }

    function renderToString()
    {
        $html = '';
        foreach ($this->notificationPool->get() as $notification) {
            $html .= $this->notificationToHtml($notification);
        }

        return $html;
    }

    /**
     * Notifications are not serialized to JSON updates.
     */
    function jsonSerialize()
    {
        return array_map(
            function (Notification $notification) {
                return [
                    'type' => $notification->type,
                    'content' => $notification->content,
                    'timeout' => $notification->timeout,
                    'details' => $notification->details,
                    'copyable' => $notification->copyable,
                ];
            },
            $this->isException() ? [$this->exception] : $this->notificationPool->get()
        );
    }

    private function notificationToHtml(Notification $notification)
    {
        return $this->templating->render(
            self::NOTIFICATION_TEMPLATE,
            ['notification' => $notification]
        );
    }

    /**
     * @return bool
     */
    private function isException()
    {
        return $this->exception instanceof Notification;
    }
}
