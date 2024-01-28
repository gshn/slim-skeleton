<?php

namespace App\Domain\User\Service;

use App\Exception\ValidationException;
use Cake\Validation\Validator;

final class UserValidator
{
    public function validate(array $data): void
    {
        $validator = new Validator();
        $validator
            ->requirePresence('status', true, '상태를 입력해주세요.')
            ->inList('status', ['사용', '중단'], '상태는 사용 혹은 중단으로 입력해주세요.')
        ;

        $errors = $validator->validate($data);

        if ($errors) {
            throw new ValidationException('입력 값을 확인해주세요.', $errors);
        }
    }
}
