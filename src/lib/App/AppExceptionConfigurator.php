<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\App;

use Exception;
use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Notification\Notification;
use Symfony\Component\HttpFoundation\Response;

class AppExceptionConfigurator implements ExceptionConfigurator
{
    /**
     * @var \EzSystems\HybridPlatformUi\Components\App
     */
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function configureException(Exception $exception, Response $exceptionResponse)
    {
        $this->app->setConfig([
            'title' => $exception->getMessage(),
            'exception' => $this->buildNotification($exception),
        ]);
    }

    private function buildNotification(Exception $exception)
    {
        return new Notification([
            'type' => 'error',
            'timeout' => 0,
            'message' => $exception->getMessage(),
            'details' => (string)$exception,
            'copyable' => true,
        ]);
    }
}
