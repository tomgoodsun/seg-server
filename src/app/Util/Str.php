<?php

namespace App\Util;

class Str
{
    /**
     * Generate a random string
     *
     * @param int $length
     * @return string
     */
    public static function random(int $length = 16): string
    {
        $bytes = random_bytes($length / 2);
        return substr(bin2hex($bytes), 0, $length);
    }
}
