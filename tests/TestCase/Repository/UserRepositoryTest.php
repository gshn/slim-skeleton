<?php

namespace App\Domain\User\Repository;

use App\Test\Fixture\UserFixture;
use App\Test\Traits\AppTestTrait;
use DI\NotFoundException;
use InvalidArgumentException;
use PDOException;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

class UserRepositoryTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;

    private UserRepository $repository;
    private UserFixture $userFixture;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpApp();
        $this->repository = new UserRepository($this->queryFactory);
        $this->userFixture = new UserFixture();
    }

    public function testInsertUserInvalidArgumentException(): void
    {
        // given
        $user = [];

        // when, then
        $this->expectException(InvalidArgumentException::class);
        $userId = $this->repository->insertUser([]);
    }

    public function testInsertUserConflict(): void
    {
        // given
        $user = $this->userFixture->records[0];

        // when, then
        $this->expectException(PDOException::class);
        $this->repository->insertUser($user);
        $this->repository->insertUser($user);
    }

    public function testExistsUser(): void
    {
        // given
        $user = $this->userFixture->records[0];
        $this->repository->insertUser($user);

        // 존재할 때
        $existsToken = $this->repository->existsToken($user['token']);
        $existsEmail = $this->repository->existsEmail($user['email']);
        $existsId = $this->repository->existsId($user['id']);

        // then
        $this->assertTrue($existsToken && $existsEmail && $existsId);

        // 존재 하지 않을 때
        $notExistsToken = $this->repository->existsToken('x');
        $notExistsEmail = $this->repository->existsEmail('x');
        $notExistsId = $this->repository->existsId(0);

        // then
        $this->assertFalse($notExistsToken || $notExistsEmail || $notExistsId);
    }

    public function testFindByIdException(): void
    {
        // given
        $notExistId = 999;

        // when
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(sprintf('존재하지 않는 아이디 입니다: %s', $notExistId));
        $user = $this->repository->findById($notExistId);
    }

    public function testInsertFindByUpdateUser(): void
    {
        // given
        $user = $this->userFixture->records[0];

        // insert, findById
        $this->repository->insertUser($user);
        $findUser = $this->repository->findById($user['id']);

        // then
        $this->assertSame($user, $findUser);

        // given
        $updateUser = $this->userFixture->records[1];
        unset($updateUser['id']);

        // update, findById
        $this->repository->updateUser($findUser['id'], $updateUser);
        $updatedUser = $this->repository->findById($findUser['id']);
        unset($updatedUser['id']);

        $this->assertSame($updateUser, $updatedUser);
    }

    public function testDeleteUser(): void
    {
        // given
        $user = $this->userFixture->records;

        // when
        $userId1 = $this->repository->insertUser($user[0]);
        $userId2 = $this->repository->insertUser($user[1]);

        // then
        $this->assertTableRowCount(2, 'users');
        $this->repository->deleteUser($userId2);
        $this->assertTableRowCount(1, 'users');
    }
}
