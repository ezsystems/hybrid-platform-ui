<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Http\Response;

use EzSystems\HybridPlatformUi\Notification\Notification;
use Symfony\Component\HttpFoundation\Response;

/**
 * Custom response type for notifications to facilitate type checking when needed.
 */
class NotificationResponse extends Response implements NoRenderResponse
{
    public function __construct(Notification $notification, $status = Response::HTTP_OK)
    {
        parent::__construct($this->convertNotificationToString($notification), $status);
    }

    private function convertNotificationToString(Notification $notification)
    {
        return sprintf(
            '<ez-notification type="%s" timeout="%s"%s%s>%s</ez-notification>',
            $notification->type,
            $notification->timeout,
            ($notification->copyable) ? ' copyable' : '',
            (trim($notification->details)) ? ' details="' . $notification->details . '"' : '',
            $notification->content
        );
    }
}
