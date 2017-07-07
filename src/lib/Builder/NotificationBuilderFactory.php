<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Builder;

use Twig\Environment;

/**
 * Factory for new notification builder instances.
 */
class NotificationBuilderFactory
{
    /**
     * @var Environment
     */
    private $environment;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * Create new builder instance.
     *
     * @return NotificationBuilder
     */
    public function create()
    {
        return new NotificationBuilder($this->environment);
    }
}
