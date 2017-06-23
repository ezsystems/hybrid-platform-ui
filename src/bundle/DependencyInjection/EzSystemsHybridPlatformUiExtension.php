<?php

namespace EzSystems\HybridPlatformUiBundle\DependencyInjection;

use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class EzSystemsHybridPlatformUiExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('components.yml');
        $loader->load('navigationhub.yml');
        $loader->load('services.yml');
        $loader->load('toolbars.yml');
        $loader->load('components.yml');
        $loader->load('pjax.yml');
    }

    public function prepend(ContainerBuilder $container)
    {
        $this->prependViewsConfiguration($container);
        $this->prependFosJsRoutingConfiguration($container);
    }

    private function prependViewsConfiguration(ContainerBuilder $container)
    {
        $configFile = __DIR__ . '/../Resources/config/views.yml';
        $config = Yaml::parse(file_get_contents($configFile));
        $container->prependExtensionConfig('ezpublish', $config);
        $container->addResource(new FileResource($configFile));
    }

    private function prependFosJsRoutingConfiguration(ContainerBuilder $container)
    {
        $configFile = __DIR__ . '/../Resources/config/fos_js_routing.yml';
        $config = Yaml::parse(file_get_contents($configFile));
        $container->prependExtensionConfig('fos_js_routing', $config);
        $container->addResource(new FileResource($configFile));
    }
}
