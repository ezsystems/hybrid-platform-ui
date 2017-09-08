<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\DependencyInjection\Compiler;

use EzSystems\HybridPlatformUi\Components\NavigationHub;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class NavigationHubPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(NavigationHub::class)) {
            return;
        }

        $this->processNavigationHubTag($container, 'ezplatform.ui.zone', '$zones');
        $this->processNavigationHubTag($container, 'ezplatform.ui.link', '$links');
    }

    private function processNavigationHubTag(ContainerBuilder $container, $tag, $index)
    {
        $unsorted = [];
        $services = $container->findTaggedServiceIds($tag);
        foreach ($services as $serviceId => $tags) {
            foreach ($tags as $tag) {
                $priority = isset($tag['priority']) ? $tag['priority'] : 0;
            }
            $unsorted[$priority][] = new Reference($serviceId);
        }

        $sorted = [];
        ksort($unsorted);
        foreach ($unsorted as $items) {
            $sorted = array_merge($sorted, $items);
        }

        $container
            ->findDefinition(NavigationHub::class)
            ->replaceArgument($index, $sorted);
    }
}
