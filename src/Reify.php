<?php

namespace Reify;

class Reify
{
    public static function map(array $data): Mapper
    {
        return new Mapper($data);
    }

    public static function json(string $json): Mapper
    {
        return self::map(json_decode($json, true, flags: JSON_THROW_ON_ERROR));
    }
}
