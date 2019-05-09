<?php

namespace App\Library\Utilities;

use App\Library\Utilities\Exceptions;

class Json
{
    /**
     * @param mixed $value
     * @param int $options
     * @param int $depth
     * @return string
     * @throws Exceptions\EncodingException
     */
    public static function encode($value, $options = 0, $depth = 512)
    {
        $encodedString = json_encode($value, $options, $depth);

        if((bool) $encodedString === false) {
            throw new Exceptions\EncodingException();
        }

        return $encodedString;
    }

    /**
     * @param string $json
     * @param bool $assoc
     * @param int $depth
     * @param int $options
     * @return mixed
     * @throws Exceptions\DecodingException
     */
    public static function decode($json, $assoc = true, $depth = 512, $options = 0)
    {
        $decodedData = json_decode($json, $assoc, $depth, $options);

        if((bool) $decodedData === false) {
            throw new Exceptions\DecodingException();
        }

        return $decodedData;
    }
}