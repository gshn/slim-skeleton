<?php

namespace App\Test\Fixture;

/**
 * Fixture.
 */
class UserFixture
{
    public string $table = 'users';

    public array $records = [
        [
            'id' => 1,
            'created_at' => '2024-01-01 00:00:00.000000',
            'status' => '사용',
            'token' => '7pQmA5MM',
            'email' => 'joe@example.com',
        ],
        [
            'id' => 2,
            'created_at' => '2024-01-01 00:00:00.000000',
            'status' => '중단',
            'token' => 'pUt9phCG',
            'email' => 'doe@example.com',
        ],
    ];
}
