<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Templating\Twig;

use Twig_Extension;
use Twig_Function;
use Twig_Environment;

/**
 * Provides the `ez_ui_icon` Twig function.
 */
class IconExtension extends Twig_Extension
{
    protected $template = '@EzSystemsHybridPlatformUi/icon/icon.html.twig';

    public function getFunctions()
    {
        return [
            new Twig_Function(
                'ez_ui_icon',
                [$this, 'renderIcon'],
                ['is_safe' => ['html'], 'needs_environment' => true]
            ),
        ];
    }

    public function renderIcon(Twig_Environment $environment, $icon)
    {
        return $environment->load($this->template)->render(['icon' => $icon]);
    }
}
