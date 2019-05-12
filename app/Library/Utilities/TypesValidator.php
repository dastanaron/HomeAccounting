<?php


namespace App\Library\Utilities;


class TypesValidator
{
    public static function isString($var)
    {
        return is_string($var);
    }

    public static function isCountable($var)
    {
        return is_array($var) || is_object($var);
    }
}