<?php

namespace spec\EzSystems\HybridPlatformUi\Pjax;

use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Mapper\MainContentMapper;
use EzSystems\HybridPlatformUi\Pjax\DomXpathPjaxResponseMainContentMapper;
use EzSystems\HybridPlatformUi\Pjax\PjaxResponseMainContentMapper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;

class DomXpathPjaxResponseMainContentMapperSpec extends ObjectBehavior
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
        $this->shouldHaveType(DomXpathPjaxResponseMainContentMapper::class);
        $this->shouldHaveType(PjaxResponseMainContentMapper::class);
        $this->shouldHaveType(MainContentMapper::class);
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

    function it_parses_notifications_and_sets_them_as_the_app_notifications(
        App $app,
        Response $response
    ) {
        $app->setConfig(Argument::that(function ($value) {
            return
                is_array($value) &&
                isset($value['notifications']) &&
                count($value['notifications']) === 4;
        }))->shouldBeCalled();

        $this->map($response);
    }

    function it_parses_notifications_and_sets_type_timeout_and_content(
        App $app,
        Response $response
    ) {
        $app->setConfig(Argument::that(function ($value) {
            $unknown = $value['notifications'][0];

            return
                $unknown['type'] === 'unknown' &&
                $unknown['timeout'] === 10 &&
                $unknown['content'] = 'notification unknown';
        }))->shouldBeCalled();

        $this->map($response);
    }

    function it_parses_notifications_and_maps_done_and_processing_state_to_positive_and_processing_type(
        App $app,
        Response $response
    ) {
        $app->setConfig(Argument::that(function ($value) {
            $stateDone = $value['notifications'][1];
            $stateStarted = $value['notifications'][2];

            return
                $stateDone['type'] === 'positive' &&
                $stateStarted['type'] === 'processing';
        }))->shouldBeCalled();

        $this->map($response);
    }

    function it_parses_notifications_and_sets_timeout_to_0_for_error(
        App $app,
        Response $response
    ) {
        $app->setConfig(Argument::that(function ($value) {
            $error = $value['notifications'][3];

            return $error['timeout'] === 0;
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
