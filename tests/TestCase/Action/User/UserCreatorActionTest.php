<?php

namespace App\Action\User;

use App\Test\Traits\AppTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

class UserCreatorActionTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;

    public function testCreateUser(): void
    {
        // given
        $request = $this->createJsonRequest(
            'POST',
            '/api/users',
            [
                'created_at' => '2024-01-01 00:00:00.000000',
                'status' => '사용',
            ]
        );

        // when
        $response = $this->app->handle($request);

        // then
        $this->assertSame(StatusCodeInterface::STATUS_CREATED, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData($response, ['token' => 'a4ayc_80']);

        // Check database
        $this->assertTableRowCount(1, 'users');

        $expected = [
            'id' => 1,
            'created_at' => '2024-01-01 00:00:00.000000',
            'status' => '사용',
        ];

        $this->assertTableRow($expected, 'users', 1);
    }
}
