<?php

namespace EzSystems\HybridPlatformUiBundle;

use EzSystems\HybridPlatformUi\Platform\AdminSiteAccessConfigurationFilter;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EzSystemsHybridPlatformUiBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $eZExtension = $container->getExtension('ezpublish');
        $eZExtension->addSiteAccessConfigurationFilter(
            new AdminSiteAccessConfigurationFilter()
        );
    }
}
