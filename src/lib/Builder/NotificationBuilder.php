<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Builder;

use Twig\Environment;

/**
 * Builder for notification messages, returns output from twig.
 */
class NotificationBuilder
{
    const TEMPLATE = 'EzSystemsHybridPlatformUiBundle::notification.html.twig';

    const DEFAULT_TIMEOUT = 10;

    const TYPE_SUCCESS = 'positive';

    const TYPE_ERROR = 'error';

    /**
     * @var Environment
     */
    private $environment;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $message;

    /**
     * @var int
     */
    private $timeout = self::DEFAULT_TIMEOUT;

    /**
     * @var bool
     */
    private $copyable;

    /**
     * @var string
     */
    private $details;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * Sets notification type as success.
     *
     * @return $this
     */
    public function setSuccess()
    {
        $this->type = self::TYPE_SUCCESS;

        return $this;
    }

    /**
     * Sets notification type as error.
     *
     * @return $this
     */
    public function setError()
    {
        $this->type = self::TYPE_ERROR;

        return $this;
    }

    /**
     * Sets error details.
     *
     * @param string $details
     *
     * @return $this
     */
    public function setErrorDetails(string $details)
    {
        $this->copyable = true;
        $this->details = $details;

        return $this;
    }

    /**
     * Sets notification message.
     *
     * @param string $message
     *
     * @return $this
     */
    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Sets notification timeout.
     *
     * @param int $timeout
     *
     * @return $this
     */
    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Builds notification message.
     *
     * @return string
     */
    public function getResult()
    {
        return $this->environment->render(
            self::TEMPLATE,
            [
                'type' => $this->type,
                'message' => $this->message,
                'timeout' => $this->timeout,
                'copyable' => $this->copyable,
                'details' => $this->details,
            ]
        );
    }
}
