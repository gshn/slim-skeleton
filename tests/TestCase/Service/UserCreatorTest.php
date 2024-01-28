<?php

namespace App\Domain\User\Service;

use App\Domain\User\Data\User;
use App\Domain\User\Repository\UserRepository;
use App\Exception\ValidationException;
use App\Test\Traits\AppTestTrait;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

class UserCreatorTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;

    private UserCreator $userCreator;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpApp();
        $this->userCreator = new UserCreator(
            new UserRepository($this->queryFactory),
            new UserValidator(),
            $this->container
        );
    }

    public function testCreateUserValidateException(): void
    {
        // given 빈 값 입력
        $user = [];

        // when, then
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('입력 값을 확인해주세요.');
        $token = $this->userCreator->createUser($user);

        // given 잘못된 상태
        $user = [
            'status' => '미사용',
        ];

        // when, then
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('상태는 사용 혹은 중단으로 입력해주세요.');
        $token = $this->userCreator->createUser($user);
    }

    public function testCreateUserUniqueToken(): void
    {
        // given
        $user = [
            'status' => '사용',
        ];

        // when
        $tokenA = $this->userCreator->createUser($user);
        $tokenB = $this->userCreator->createUser($user);

        // then
        $this->assertNotSame($tokenA, $tokenB);
    }

    public function testCreateUser(): void
    {
        // given
        $user = [
            'status' => '사용',
        ];
        $token = User::generateToken(1, $this->container->get('settings')['salt']);

        // when
        $createToken = $this->userCreator->createUser($user);

        // then
        $this->assertSame($token, $createToken);
    }
}
