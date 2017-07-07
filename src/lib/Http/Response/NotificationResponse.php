<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Http\Response;

use Symfony\Component\HttpFoundation\Response;

/**
 * Custom response type for notifications to facilitate type checking when needed.
 */
class NotificationResponse extends Response implements NoRenderResponse
{
    /**
     * Successful response.
     *
     * @param string $content
     *
     * @return static
     */
    public static function success(string $content)
    {
        return new static($content);
    }

    /**
     * Error response.
     *
     * @param string $content
     * @param int $status
     *
     * @return static
     */
    public static function error(string $content, int $status)
    {
        return new static($content, $status);
    }
}
