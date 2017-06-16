<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Pjax;

use Symfony\Component\HttpFoundation\Response;

class MarkupPjaxResponseMatcher implements PjaxResponseMatcher
{
    /**
     * @param $response \Symfony\Component\HttpFoundation\Response
     *
     * @return bool
     */
    public function matches(Response $response)
    {
        return $this->hasPjaxMarkup($response->getContent());
    }

    private function hasPjaxMarkup($html)
    {
        return strpos($html, '<div data-name="html">') !== false;
    }
}
