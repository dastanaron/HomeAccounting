<?php

declare(strict_types=1);

namespace App\Library\Utilities;

use App\Library\Utilities\Exceptions\DecodingException;

/**
 * Class Base64
 * @package App\Library\Utilities
 */
class Base64
{
    /**
     * @param string $string
     * @return string
     */
    public static function encode(string $string) : string
    {
        return base64_encode($string);
    }

    /**
     * @param string $string
     * @param bool $strict
     * @return string
     * @throws DecodingException
     */
    public static function decode(string $string, $strict = false) : string
    {
        $decodedString = base64_decode($string, $strict);

        if ((bool) $decodedString === false) {
            throw new DecodingException('cannot decode string');
        }

        return $decodedString;
    }
}