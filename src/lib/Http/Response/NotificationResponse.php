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
        parent::__construct($notification, $status);
    }
}
