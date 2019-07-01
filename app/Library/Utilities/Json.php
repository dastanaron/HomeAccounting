<?php

namespace App\Library\Utilities;

use App\Library\Utilities\Exceptions;

class Json
{

    /**
     * @param string $json
     * @return bool
     */
    public static function isValid($json)
    {
        try {
            $decoded = self::decode($json);
        }
        catch (Exceptions\DecodingException $e) {
            return false;
        }

        if (is_null($decoded) || !is_array($decoded)) {
            return false;
        }

        return true;
    }

    /**
     * @param mixed $value
     * @param int $options
     * @param int $depth
     * @return string
     * @throws Exceptions\EncodingException
     */
    public static function encode($value, $options = 0, $depth = 512)
    {
        $encodedData = json_encode($value, $options, $depth);

        $error = json_last_error();

        switch ($error) {
            case JSON_ERROR_NONE:
                break;
            case JSON_ERROR_DEPTH:
                throw new Exceptions\EncodingException('Max stack depth exceeded', $error);
            case JSON_ERROR_STATE_MISMATCH:
                throw new Exceptions\EncodingException('Syntax error', $error);
            case JSON_ERROR_CTRL_CHAR:
                throw new Exceptions\EncodingException('Incorrect control character', $error);
            case JSON_ERROR_SYNTAX:
                throw new Exceptions\EncodingException('Syntax error', $error);
            case JSON_ERROR_UTF8:
                throw new Exceptions\EncodingException('Incorrect UTF-8 symbol, maybe encoding error', $error);
            default:
                throw new Exceptions\EncodingException('Unknown error', $error);
        }

        return $encodedData;
    }

    /**
     * @param string $json
     * @param bool $assoc
     * @param int $depth
     * @param int $options
     * @return array|object
     * @throws Exceptions\DecodingException
     */
    public static function decode($json, $assoc = true, $depth = 512, $options = 0)
    {
        if ($json === null || $json === '') {
            return null;
        }

        $decodedData = json_decode($json, $assoc, $depth, $options);

        $error = json_last_error();
        switch ($error) {
            case JSON_ERROR_NONE:
                break;
            case JSON_ERROR_DEPTH:
                throw new Exceptions\DecodingException('Max stack depth exceeded', $error);
            case JSON_ERROR_STATE_MISMATCH:
                throw new Exceptions\DecodingException('Invalid or malformed JSON', $error);
            case JSON_ERROR_CTRL_CHAR:
                throw new Exceptions\DecodingException('Incorrect control character', $error);
            case JSON_ERROR_SYNTAX:
                throw new Exceptions\DecodingException('Syntax error', $error);
            case JSON_ERROR_UTF8:
                throw new Exceptions\DecodingException('Incorrect UTF-8 symbol, maybe encoding error', $error);
            default:
                throw new Exceptions\DecodingException('Unknown error', $error);
        }

        return $decodedData;
    }
}