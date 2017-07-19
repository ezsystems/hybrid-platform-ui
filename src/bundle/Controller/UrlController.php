<?php
/**
 * File containing the UrlAliasController class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Controller;

use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use EzSystems\HybridPlatformUi\View\Content\Urls\CustomUrlParameterSupplier;
use EzSystems\HybridPlatformUi\View\Content\Urls\SystemUrlParameterSupplier;

class UrlController extends TabController
{
    public function contentViewTabAction(
        ContentView $view,
        CustomUrlParameterSupplier $customUrlParameterSupplier,
        SystemUrlParameterSupplier $systemUrlParameterSupplier
    ) {
        $customUrlParameterSupplier->supply($view);
        $systemUrlParameterSupplier->supply($view);

        return $view;
    }
}
