<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Notification;

use eZ\Publish\API\Repository\Values\ValueObject;

/**
 * Notification object for messages to users, to be stored in a Notification Pool.
 * Can be cast to a string for sending as a Response but most use cases will just access properties of it.
 *
 * @property-read string $type
 * @property-read string $message
 * @property-read int $timeout
 * @property-read bool $copyable
 * @property-read string $details
 */
class Notification extends ValueObject
{
    const DEFAULT_TIMEOUT = 10;

    const ERROR_TIMEOUT = 0;

    const TYPE_SUCCESS = 'positive';

    const TYPE_ERROR = 'error';

    /**
     * Message type.
     *
     * @var string
     */
    protected $type;

    /**
     * Message.
     *
     * @var string
     */
    protected $message;

    /**
     * Message timeout.
     *
     * @var int
     */
    protected $timeout;

    /**
     * Are details Copyable.
     *
     * @var bool
     */
    protected $copyable;

    /**
     * Extended details for Developers.
     *
     * @var string
     */
    protected $details;
}
