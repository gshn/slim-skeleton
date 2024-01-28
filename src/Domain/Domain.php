<?php

namespace App\Domain;

abstract class Domain
{
    // 배열의 키가 클래스 맴버가 아닐 경우 필터링
    public static function filterProperty(array $array): array
    {
        return array_intersect_key($array, get_class_vars(static::class));
    }
}
