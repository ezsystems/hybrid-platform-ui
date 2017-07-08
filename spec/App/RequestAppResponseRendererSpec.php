<?php

namespace spec\EzSystems\HybridPlatformUi\App;

use EzSystems\HybridPlatformUi\App\RequestAppResponseRenderer;
use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Http\AjaxUpdateRequestMatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class RequestAppResponseRendererSpec extends ObjectBehavior
{
    function let(
        Request $request,
        RequestStack $requestStack,
        AjaxUpdateRequestMatcher $ajaxUpdateRequestMatcher,
        ParameterBag $requestAttributes,
        HeaderBag $requestHeaders,
        Response $response
    ) {
        $request->attributes = $requestAttributes;
        $response->headers = $requestHeaders;
        $requestStack->getMasterRequest()->willReturn($request);
        $this->beConstructedWith($requestStack, $ajaxUpdateRequestMatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RequestAppResponseRenderer::class);
    }

    function it_sets_the_response_to_the_app_html(
        AjaxUpdateRequestMatcher $ajaxUpdateRequestMatcher,
        App $app,
        HeaderBag $requestHeaders,
        Request $request,
        Response $response
    ) {
        $ajaxUpdateRequestMatcher->matches($request)
            ->shouldBeCalled()
            ->willReturn(false);

        $app->renderToString()
            ->shouldBeCalled()
            ->willReturn('html');

        $response->setContent('html')->shouldBeCalled()->willReturn($response);
        $requestHeaders->replace(Argument::type('array'))->shouldBeCalled();

        $this->render($response, $app);
    }

    function it_sets_the_response_to_the_app_json(
        AjaxUpdateRequestMatcher $ajaxUpdateRequestMatcher,
        App $app,
        HeaderBag $requestHeaders,
        Request $request,
        Response $response
    ) {
        $ajaxUpdateRequestMatcher->matches($request)
            ->shouldBeCalled()
            ->willReturn(true);

        $app->jsonSerialize()
            ->shouldBeCalled()
            ->willReturn([]);

        $response->setContent('[]')->shouldBeCalled()->willReturn($response);
        $requestHeaders->replace(Argument::that(
            function ($headers) {
                return isset($headers['content-type'][0])
                    && $headers['content-type'][0] === 'application/json';
            }
        ))->shouldBeCalled();

        $this->render($response, $app);
    }
}
