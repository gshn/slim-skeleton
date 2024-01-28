<?php

namespace App\Domain\User\Service;

use App\Domain\User\Data\User;
use App\Domain\User\Repository\UserRepository;
use Psr\Container\ContainerInterface;

final class UserCreator
{
    private UserRepository $repository;
    private UserValidator $validator;
    private ContainerInterface $container;

    public function __construct(UserRepository $repository, UserValidator $validator, ContainerInterface $container)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->container = $container;
    }

    public function createUser(array $user): string
    {
        $this->validator->validate($user);

        $userId = $this->repository->insertUser($user);

        // 사용자 아이디로 토큰 생성
        $token = User::generateToken($userId, $this->container->get('settings')['salt']);
        $this->repository->updateUser($userId, ['token' => $token]);

        return $token;
    }
}
