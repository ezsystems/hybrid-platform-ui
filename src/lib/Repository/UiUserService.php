<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\UserService;

/**
 * Service for loading user.
 */
class UiUserService
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var Repository
     */
    private $repository;

    public function __construct(Repository $repository, UserService $userService)
    {
        $this->repository = $repository;
        $this->userService = $userService;
    }

    /**
     * Tries to load a user ignoring privileges.
     * This is so that the user of a version/detail information can be displayed even if the user
     * doesn't have the privileges to do so.
     *
     * @param mixed $userId
     *
     * @return \eZ\Publish\API\Repository\Values\User\User|null
     */
    public function findUserById($userId)
    {
        return $this->repository->sudo(function () use ($userId) {
            return $this->loadUser($userId);
        });
    }

    /**
     * Loads user and handles not found exception.
     * Intended usage, is to enable testing using php spec as testing callable is difficult.
     * Use findUserById().
     *
     * @param mixed $userId
     *
     * @return \eZ\Publish\API\Repository\Values\User\User|null
     */
    public function loadUser($userId)
    {
        try {
            return $this->userService->loadUser($userId);
        } catch (NotFoundException $e) {
            return null;
        }
    }
}
