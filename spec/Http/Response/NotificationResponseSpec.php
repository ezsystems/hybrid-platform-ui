<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace spec\EzSystems\HybridPlatformUi\Http\Response;

use EzSystems\HybridPlatformUi\Http\Response\NoRenderResponse;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Response;

class NotificationResponseSpec extends ObjectBehavior
{
    function it_is_a_response()
    {
        $this->shouldBeAnInstanceOf(Response::class);
    }

    function it_bypasses_app_render()
    {
        $this->shouldBeAnInstanceOf(NoRenderResponse::class);
    }
}
