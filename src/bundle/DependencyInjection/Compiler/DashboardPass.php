<?php

namespace EzSystems\HybridPlatformUiBundle\DependencyInjection\Compiler;

use eZ\Publish\Core\Base\Exceptions\InvalidArgumentException;
use EzSystems\HybridPlatformUi\Dashboard\Dashboard;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DashboardPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @throws \eZ\Publish\Core\Base\Exceptions\InvalidArgumentException
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(Dashboard::class)) {
            return;
        }

        $tagServices = $this->processTabs(
            $container,
            $container->findTaggedServiceIds('ezplatform.ui.dashboard.tab')
        );

        $sectionServices = $this->processSections(
            $container,
            $container->findTaggedServiceIds('ezplatform.ui.dashboard.section'),
            $tagServices
        );

        $dashboardDefinition = $container->findDefinition(Dashboard::class);
        $dashboardDefinition
            ->addMethodCall('setSections', [$sectionServices]);
    }

    /**
     * @param ContainerBuilder $container
     * @param array $tabs
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    private function processTabs(ContainerBuilder $container, array $tabs): array
    {
        $tabServices = [];

        foreach ($tabs as $refId => $tags) {
            foreach ($tags as $tag) {
                if (!isset($tag['section'])) {
                    throw new InvalidArgumentException('section', "Tag 'ezplatform.ui.dashboard.tab' must have section information (refId: ${refId})");
                }
                if (!isset($tag['identifier'])) {
                    throw new InvalidArgumentException('identifier', "Tag 'ezplatform.ui.dashboard.tab' must have identifier (refId: ${refId})");
                }
                $order = isset($tag['order']) ? $tag['order'] : 0;
                $tabServices[$tag['section']][$order][$tag['identifier']] = new Reference($refId);
            }
        }

        return $tabServices;
    }

    /**
     * @param ContainerBuilder $container
     * @param array $sections
     * @param array $tabServices
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    private function processSections(ContainerBuilder $container, array $sections, array $tabServices): array
    {
        $sectionServices = [];
        foreach ($sections as $refId => $tags) {
            foreach ($tags as $tag) {
                if (!isset($tag['identifier'])) {
                    throw new InvalidArgumentException('identifier', "Tag 'ezplatform.ui.dashboard.section' must have identifier (refId: ${refId})");
                }
                $order = isset($tag['order']) ? $tag['order'] : 0;

                $unsorted = $tabServices[$tag['identifier']];
                ksort($unsorted);

                $sortedServices = [];
                foreach ($unsorted as $items) {
                    $sortedServices = array_merge($sortedServices, $items);
                }

                $sectionDefinition = $container->findDefinition($refId);
                $sectionDefinition
                    ->addMethodCall('setTabs', [$sortedServices]);

                $sectionServices[$order][$tag['identifier']] = new Reference($refId);
            }
        }
        ksort($sectionServices);

        $sortedServices = [];
        foreach ($sectionServices as $items) {
            $sortedServices = array_merge($sortedServices, $items);
        }

        return $sortedServices;
    }
}
