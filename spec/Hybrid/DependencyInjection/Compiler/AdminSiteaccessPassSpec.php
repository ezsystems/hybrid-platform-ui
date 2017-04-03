<?php

namespace spec\EzSystems\PlatformUIBundle\Hybrid\DependencyInjection\Compiler;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\ConfigResolver;
use eZ\Publish\Core\MVC\ConfigResolverInterface;
use EzSystems\PlatformUIBundle\Hybrid\DependencyInjection\Compiler\AdminSiteaccessPass;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AdminSiteaccessPassSpec extends ObjectBehavior
{
    function let(
        ContainerBuilder $containerBuilder
    ) {
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AdminSiteaccessPass::class);
        $this->shouldHaveType(CompilerPassInterface::class);
    }

    function it_creates_a_unique_admin_siteaccess_when_there_is_one_repository_and_one_root_location(
        ContainerBuilder $containerBuilder
    ) {
        $containerBuilder->getParameter('ezpublish.siteaccess.groups')->willReturn(['site_group' => ['site']]);
        $containerBuilder->getParameter('ezpublish.siteaccess.list')->willReturn(['site']);

        $containerBuilder->setParameter(
            'ezpublish.siteaccess.list',
            Argument::containing('admin')
        )->shouldBeCalled();

        $containerBuilder->setParameter(
            'ezpublish.siteaccess.groups',
            Argument::that(function ($parameter) {
                return isset($parameter['site_group']) && in_array('admin', $parameter['site_group']);
            })
        )->shouldBeCalled();

        $this->process($containerBuilder);
    }
}
