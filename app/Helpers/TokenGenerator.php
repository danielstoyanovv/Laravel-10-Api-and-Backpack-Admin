<?php

namespace App\Helpers;

class TokenGenerator
{
    /**
     * @return string
     */
    public static function generate(): string
    {
        return base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }
}
