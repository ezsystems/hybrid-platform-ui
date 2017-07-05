<?php

namespace spec\EzSystems\HybridPlatformUi\Toolbars;

use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Toolbars\RouteBasedConfigurator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class RouteBasedConfiguratorSpec extends ObjectBehavior
{
    function let(
        App $app,
        Request $request,
        ParameterBag $requestAttributes
    ) {
        $request->attributes = $requestAttributes;
        $this->beConstructedWith($app);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RouteBasedConfigurator::class);
    }

    function it_can_be_added_routes_mappings()
    {
        $this->addRoutesMappings([
            'some_route' => ['toolbar' => 1],
            'some_other_route' => ['other_toolbar' => 1]
        ]);
    }

    function it_ignores_requests_that_do_not_have_a_route(
        App $app,
        Request $request,
        ParameterBag $requestAttributes
    ) {
        $requestAttributes->has('_route')->willReturn(false);
        $app->setConfig(Argument::any())->shouldNotBeCalled();

        $this->fromRequest($request);
    }

    function it_does_not_configure_the_toolbars_if_there_is_no_matching_routes_mapping(
        App $app,
        Request $request,
        ParameterBag $requestAttributes
    ) {
        $requestAttributes->has('_route')->willReturn(true);
        $requestAttributes->get('_route')->willReturn('other_route');
        $app->setConfig(Argument::any())->shouldNotBeCalled();

        $this->fromRequest($request);
    }

    function it_configures_the_toolbars_with_the_matching_mapping(
        App $app,
        Request $request,
        ParameterBag $requestAttributes
    ) {
        $requestAttributes->has('_route')->willReturn(true);
        $requestAttributes->get('_route')->willReturn('some_route');
        $this->addRoutesMappings(['some_route' => ['toolbar' => 1]]);
        $app->setConfig(['toolbars' => ['toolbar' => 1]])->shouldBeCalled();

        $this->fromRequest($request);
    }
}
