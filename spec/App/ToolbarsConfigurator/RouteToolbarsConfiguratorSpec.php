<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace spec\EzSystems\HybridPlatformUi\App\ToolbarsConfigurator;

use EzSystems\HybridPlatformUi\App\ToolbarsConfigurator\RouteToolbarsConfigurator;
use EzSystems\HybridPlatformUi\App\ToolbarsConfigurator;
use EzSystems\HybridPlatformUi\Components\App;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class RouteToolbarsConfiguratorSpec extends ObjectBehavior
{
    public function let(
        ParameterBag $requestAttributes,
        RequestStack $requestStack,
        Request $request
    ) {
        $request->attributes = $requestAttributes;
        $requestStack->getMasterRequest()->willReturn($request);

        $this->beConstructedWith($requestStack);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RouteToolbarsConfigurator::class);
        $this->shouldHaveType(ToolbarsConfigurator::class);
    }

    function it_enables_the_discovery_bar_for_ezurlalias(
        App $app,
        ParameterBag $requestAttributes
    ) {
        $requestAttributes->get('_route')->shouldBeCalled()->willReturn('ez_urlalias');
        $app->setConfig(Argument::that(
            function ($config) {
                return isset($config['toolbars'])
                    && $config['toolbars'] == ['discovery' => 1];
            }
        ))->shouldBeCalled();

        $this->configureToolbars($app);
    }

    function it_does_not_enable_any_toolbar_for_unknown_routes(
        App $app,
        ParameterBag $requestAttributes
    ) {
        $requestAttributes->get('_route')->shouldBeCalled()->willReturn('someroute');
        $app->setConfig(Argument::any())->shouldNotBeCalled();

        $this->configureToolbars($app);
    }
}
