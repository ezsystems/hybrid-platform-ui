<?php

namespace spec\EzSystems\HybridPlatformUi\Pjax;

use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Pjax\PjaxResponseMainContentMapper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;

class PjaxResponseMainContentMapperSpec extends ObjectBehavior
{
    function let(
        App $app,
        Response $response
    ) {
        $response->getContent()->willReturn(
            file_get_contents(__DIR__ . '/_fixtures/pjax_response.html')
        );

        $this->beConstructedWith($app);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PjaxResponseMainContentMapper::class);
    }

    function it_maps_to_a_PjaxView(
        App $app,
        Response $response
    ) {
        $app->setConfig(Argument::any())->shouldBeCalled();

        $this->map($response);
    }

    function it_parses_the_title_and_sets_it_as_the_app_title(
        App $app,
        Response $response
    ) {
        $app->setConfig(Argument::that(function ($value) {
            return
                is_array($value) &&
                isset($value['title']) &&
                $value['title'] === 'Response title';
        }))->shouldBeCalled();

        $this->map($response);
    }

    function it_parses_the_content_and_sets_it_as_the_app_MainContent_result(
        App $app,
        Response $response
    ) {
        $app->setConfig(Argument::that(function ($value) {
            return
                is_array($value) &&
                isset($value['mainContent']) &&
                isset($value['mainContent']['result']) &&
                strpos($value['mainContent']['result'], 'Server side content') !== false;
        }))->shouldBeCalled();

        $this->map($response);
    }

    function getMatchers()
    {
        return [
            'haveTitle' => function (App $app, $expectedTitle) {
                return $mainContent->getTitle() == $expectedTitle;
            },
            'haveResult' => function (App $app, $expectedContent) {
                return strstr((string)$mainContent, $expectedContent) !== null;
            },
        ];
    }
}
