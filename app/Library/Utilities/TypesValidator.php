<?php


namespace App\Library\Utilities;


/**
 * Class TypesValidator
 * @package App\Library\Utilities
 */
class TypesValidator
{
    /**
     * @param $var
     * @return bool
     */
    public static function isString($var)
    {
        return is_string($var);
    }

    /**
     * @param $var
     * @return bool
     */
    public static function isCountable($var)
    {
        return self::isArray($var) || self::isObject($var);
    }

    /**
     * @param $var
     * @return bool
     */
    public static function isArray($var)
    {
        return is_array($var);
    }

    /**
     * @param $var
     * @return bool
     */
    public static function isObject($var)
    {
        return is_object($var);
    }

    /**
     * @param $var
     * @return bool
     */
    public function isNumber($var)
    {
        return is_numeric($var);
    }

    /**
     * @param $var
     * @return bool
     */
    public static function isResource($var)
    {
        return is_resource($var);
    }

    /**
     * @param $var
     * @return bool
     */
    public static function isFloat($var)
    {
        return is_float($var);
    }

    /**
     * @param $var
     * @return bool
     */
    public static function isBoolean($var)
    {
        return is_bool($var);
    }

    /**
     * @param $var
     * @return string
     */
    public static function getType($var)
    {
        return gettype($var);
    }
}