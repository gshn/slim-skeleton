<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Data\User;
use App\Factory\QueryFactory;
use DI\NotFoundException;

class UserRepository
{
    private QueryFactory $queryFactory;

    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    public function insertUser(array $user): int
    {
        return (int)$this->queryFactory->insert('users', User::filterProperty($user));
    }

    public function existsToken(string $token): bool
    {
        $query = $this->queryFactory->select('users', ['id'], ['token' => $token]);

        return !$query ?: (bool)$query->fetch();
    }

    public function existsEmail(string $email): bool
    {
        $query = $this->queryFactory->select('users', ['id'], ['email' => $email]);

        return !$query ?: (bool)$query->fetch();
    }

    public function existsId(int $userId): bool
    {
        $query = $this->queryFactory->select('users', ['id'], ['id' => $userId]);

        return !$query ?: (bool)$query->fetch();
    }

    public function updateUser(int $userId, array $user): void
    {
        $this->queryFactory->update('users', User::filterProperty($user), ['id' => $userId]);
    }

    /**
     * @param int $userId
     *
     * @throws NotFoundException
     */
    public function findById(int $userId): array
    {
        $query = $this->queryFactory->select('users', ['*'], ['id' => $userId]);
        $row = !$query ?: $query->fetch();

        if (!$row) {
            throw new NotFoundException(sprintf('존재하지 않는 아이디 입니다: %s', $userId));
        }

        return $row;
    }

    public function deleteUser(int $userId): void
    {
        $this->queryFactory->delete('users', ['id' => $userId]);
    }
}
