<?php

namespace spec\EzSystems\HybridPlatformUi\View;

use eZ\Publish\Core\MVC\Symfony\View\View;
use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\View\CoreViewMainContentMapper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CoreViewMainContentMapperSpec extends ObjectBehavior
{
    function let(App $app)
    {
        $this->beConstructedWith($app);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CoreViewMainContentMapper::class);
    }

    function it_throws_an_exception_on_map_if_the_argument_is_not_a_view()
    {
        $this->shouldThrow('\InvalidArgumentException')->during('map', [new \stdClass()]);
    }

    function it_maps_the_view_template_to_the_main_content(App $app, View $view)
    {
        $view->getTemplateIdentifier()->willReturn('template_identifier.html.twig');
        $view->getParameters()->shouldBeCalled();
        $app->setConfig(Argument::that(function ($value) {
            return is_array($value) &&
                isset($value['mainContent']) &&
                isset($value['mainContent']['template']) &&
                $value['mainContent']['template'] === 'template_identifier.html.twig';
        }));
        $this->map($view);
    }

    function it_maps_the_view_parameters_to_the_main_content(App $app, View $view)
    {
        $view->getTemplateIdentifier()->shouldBeCalled();
        $view->getParameters()->willReturn(['param' => 'value']);
        $app->setConfig(Argument::that(function ($value) {
            return is_array($value) &&
                isset($value['mainContent']) &&
                isset($value['mainContent']['parameters']) &&
                $value['mainContent']['parameters'] === ['param' => 'value'];
        }));
        $this->map($view);
    }
}
