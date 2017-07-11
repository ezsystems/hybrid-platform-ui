<?php
/**
* @copyright Copyright (C) eZ Systems AS. All rights reserved.
* @license For full copyright and license information view LICENSE file distributed with this source code.
*/
namespace EzSystems\HybridPlatformUi\View\Content;

use eZ\Publish\Core\MVC\Symfony\View\ContentView;

/**
 * Supplies the view with any parameters it may require to be displayed.
 */
interface ParameterSupplier
{
    /**
     * This is used to add parameters to the view.
     * Therefore $contentView->addParameters() should be called.
     *
     * @param ContentView $contentView
     */
    public function supply(ContentView $contentView);
}
