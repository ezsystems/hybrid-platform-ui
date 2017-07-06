<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Response;

use Symfony\Component\HttpFoundation\Response;

/**
 * Reset response to tell the app to reload the location.
 */
class ResetResponse extends Response implements NoRenderResponse
{
    public function __construct(string $url)
    {
        parent::__construct(
            '',
            static::HTTP_RESET_CONTENT,
            [
                'App-Location' => $url,
            ]
        );
    }
}
