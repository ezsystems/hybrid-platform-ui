<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Response;

use Symfony\Component\HttpFoundation\Response;

/**
 * Custom response type for notifications to facilitate type checking when needed.
 */
class NotificationResponse extends Response
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
     *
     * @return static
     */
    public static function error(string $content)
    {
        return new static($content, static::HTTP_BAD_REQUEST);
    }
}
