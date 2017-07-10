<?php

namespace spec\EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\UserService;
use eZ\Publish\Core\Base\Exceptions\NotFoundException;
use eZ\Publish\Core\Repository\Values\User\User;
use PhpSpec\ObjectBehavior;

class UiUserServiceSpec extends ObjectBehavior
{
    function let(Repository $repository, UserService $userService)
    {
        $this->beConstructedWith($repository, $userService);
    }

    function it_should_handle_not_found_exception(UserService $userService)
    {
        $userId = 1;

        $userService->loadUser($userId)->willThrow(NotFoundException::class);

        $this->loadUser($userId)->shouldBe(null);
    }

    function it_should_load_user_when_it_exists(UserService $userService)
    {
        $userId = 1;

        $user = new User();

        $userService->loadUser($userId)->willReturn($user);

        $this->loadUser($userId)->shouldBe($user);
    }
}
