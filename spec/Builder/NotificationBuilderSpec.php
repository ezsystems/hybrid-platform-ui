<?php

namespace spec\EzSystems\HybridPlatformUi\Builder;

use EzSystems\HybridPlatformUi\Builder\NotificationBuilder;
use PhpSpec\ObjectBehavior;
use Twig\Environment;

class NotificationBuilderSpec extends ObjectBehavior
{
    function let(Environment $environment)
    {
        $this->beConstructedWith($environment);
    }

    function it_build_success_notification(Environment $environment)
    {
        $message = 'test';

        $environment->render(
            NotificationBuilder::TEMPLATE,
            [
                'type' => NotificationBuilder::TYPE_SUCCESS,
                'message' => $message,
                'timeout' => NotificationBuilder::DEFAULT_TIMEOUT,
                'copyable' => null,
                'details' => null,
            ]
        )->shouldBeCalled();

        $this
            ->setSuccess()
            ->setMessage($message)
            ->getResult();
    }

    function it_build_error_notification(Environment $environment)
    {
        $message = 'test';
        $details = 'test details';

        $environment->render(
            NotificationBuilder::TEMPLATE,
            [
                'type' => NotificationBuilder::TYPE_ERROR,
                'message' => $message,
                'timeout' => NotificationBuilder::DEFAULT_TIMEOUT,
                'copyable' => true,
                'details' => $details,
            ]
        )->shouldBeCalled();

        $this
            ->setError()
            ->setErrorDetails($details)
            ->setMessage($message)
            ->getResult();
    }
}
