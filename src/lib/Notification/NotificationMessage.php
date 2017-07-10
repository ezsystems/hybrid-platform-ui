<?php

namespace EzSystems\HybridPlatformUi\Notification;

use eZ\Publish\API\Repository\Values\ValueObject;

class NotificationMessage extends ValueObject
{
    const DEFAULT_TIMEOUT = 10;

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

    public function __toString()
    {
        return sprintf(
            '<ez-notification type="%s" timeout="%s"%s%s><p>%s</p></ez-notification>',
            $this->type,
            $this->timeout,
            ($this->copyable) ? ' copyable' : '',
            (trim($this->details)) ? ' details="' . $this->details . '"' : '',
            $this->message
        );
    }
}
