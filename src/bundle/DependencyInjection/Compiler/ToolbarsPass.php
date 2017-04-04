<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\HybridPlatformUiBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use LogicException;

class ToolbarsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('ezsystems.platformui.component.app')) {
            return;
        }

        $toolbars = [];
        $toolbarsItems = [];

        foreach ($container->findTaggedServiceIds('ezplatform.ui.toolbar_item') as $serviceId => $tags) {
            foreach ($tags as $tag) {
                if (!isset($tag['toolbar'])) {
                    throw new LogicException("Missing mandatory tag attribute 'toolbar' for service " . $serviceId);
                }
                $toolbarsItems[$tag['toolbar']][] = new Reference($serviceId);
            }
        }

        foreach ($container->findTaggedServiceIds('ezplatform.ui.toolbar') as $serviceId => $tags) {
            foreach ($tags as $tag) {
                if (!isset($tag['alias'])) {
                    throw new LogicException("Missing mandatory tag attribute 'toolbar' for service ". $serviceId);
                }

                $toolbarDefinition = $container->findDefinition($serviceId);
                $toolbarDefinition->replaceArgument(0, $tag['alias']);

                if (isset($toolbarsItems[$tag['alias']])) {
                    $toolbarDefinition->replaceArgument(1, $toolbarsItems[$tag['alias']]);
                    unset($toolbarsItems[$tag['alias']]);
                }

                $toolbars[] = new Reference($serviceId);
            }
        }

        if (count($toolbarsItems) > 0) {
            $message =
                "Services tagged as ezplatform.ui.toolbar_item were found that aren't attached to any toolbar: " .
                implode(', ', array_keys($toolbarsItems));

            throw new LogicException($message);
        }

        $container
            ->findDefinition('ezsystems.platformui.component.app')
            ->replaceArgument(3, $toolbars);
    }
}
