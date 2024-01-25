<?php

namespace App\Domain\User\Data;

use App\Domain\CommonData;

final class User extends CommonData
{
    public ?int $id;
    public ?string $created_at;
    public ?string $status;
    public ?string $token;
    public ?string $email;

    public static function generateToken(int $id, string $salt): string
    {
        $base64 = base64_encode(hash('sha256', $id . $salt, true));
        $urlsafe = strtr($base64, '+/', '-_');
        $urlsafe = rtrim($urlsafe, '=');

        return substr($urlsafe, 0, 8);
    }
}
