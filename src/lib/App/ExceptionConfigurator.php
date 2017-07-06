<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\App;

use Symfony\Component\HttpFoundation\Response;
use Exception;

/**
 * Configures the app with an exception.
 */
interface ExceptionConfigurator
{
    public function configureException(Exception $exception, Response $exceptionResponse);
}
